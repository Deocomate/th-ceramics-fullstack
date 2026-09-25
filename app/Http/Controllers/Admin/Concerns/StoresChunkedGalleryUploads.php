<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Support\ProductGallery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

trait StoresChunkedGalleryUploads
{
    /**
     * @param  callable(array<int, mixed> $images, array<int, string> $videoUrls, array<int, mixed> $videoFiles): Model  $appender
     */
    protected function storeGalleryChunkResponse(Request $request, callable $appender): JsonResponse
    {
        $validated = $request->validate([
            'chunk' => ['required', 'file', 'max:1536'],
            'upload_id' => ['required', 'uuid'],
            'chunk_index' => ['required', 'integer', 'min:0'],
            'total_chunks' => ['required', 'integer', 'min:1', 'max:100'],
            'kind' => ['required', 'in:image,video'],
            'original_name' => ['required', 'string', 'max:255'],
        ], [
            'chunk.max' => 'Mỗi phần tải lên không được vượt quá 1.5MB.',
            'original_name.required' => 'Thiếu tên file gốc.',
        ]);

        $chunkIndex = (int) $validated['chunk_index'];
        $totalChunks = (int) $validated['total_chunks'];
        if ($chunkIndex >= $totalChunks) {
            throw ValidationException::withMessages([
                'chunk_index' => 'Phần tải lên không hợp lệ.',
            ]);
        }

        $kind = $validated['kind'];
        $originalName = $validated['original_name'];
        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = $kind === 'video' ? ['mp4', 'webm'] : ['webp'];
        if (! in_array($extension, $allowed, true)) {
            throw ValidationException::withMessages([
                'original_name' => $kind === 'video'
                    ? 'Video phải có định dạng mp4 hoặc webm.'
                    : 'Ảnh chưa được chuyển sang WebP. Hãy chọn lại ảnh để hệ thống tự tối ưu.',
            ]);
        }

        $directory = $this->galleryChunkDirectory((string) $validated['upload_id']);
        $disk = Storage::disk('local');
        if (! $disk->put("{$directory}/_meta.json", json_encode(['created_at' => now()->timestamp], JSON_THROW_ON_ERROR))
            || ! $disk->putFileAs($directory, $validated['chunk'], (string) $chunkIndex)) {
            throw ValidationException::withMessages(['chunk' => 'Máy chủ chưa lưu được phần tải lên này. Hãy thử lại.']);
        }

        if ($chunkIndex < $totalChunks - 1) {
            return response()->json([
                'success' => true,
                'received' => $chunkIndex + 1,
                'total_chunks' => $totalChunks,
            ]);
        }

        $lock = fopen($disk->path("{$directory}/_lock"), 'c');
        if ($lock === false || ! flock($lock, LOCK_EX)) {
            if (is_resource($lock)) {
                fclose($lock);
            }
            throw ValidationException::withMessages(['chunk' => 'Không thể tiếp tục tải file lúc này. Hãy thử lại.']);
        }

        try {
            $completedPath = "{$directory}/completed.json";
            if ($disk->exists($completedPath)) {
                $completed = json_decode($disk->get($completedPath), true);
                if (is_array($completed) && ! empty($completed['success'])) {
                    return response()->json($completed);
                }
            }

            $assembled = $this->assembleGalleryChunks($directory, $totalChunks, $originalName, $kind);

            if ($kind === 'image') {
                $dimensions = @getimagesize($assembled->getPathname());
                if ($assembled->getMimeType() !== 'image/webp' || $dimensions === false
                    || max($dimensions[0], $dimensions[1]) > 2560
                    || $dimensions[0] * $dimensions[1] > 6_553_600) {
                    @unlink($assembled->getPathname());
                    throw ValidationException::withMessages(['chunk' => 'Không đọc được ảnh WebP đã tối ưu. Hãy chọn lại ảnh.']);
                }
            }

            try {
                $images = $kind === 'image' ? [$assembled] : [];
                $videos = $kind === 'video' ? [$assembled] : [];
                $model = $appender($images, [], $videos);
            } finally {
                if (is_file($assembled->getPathname())) {
                    @unlink($assembled->getPathname());
                }
            }

            $gallery = is_array($model->images) ? $model->images : [];
            $coverPath = ProductGallery::firstImagePath($gallery);
            $items = ProductGallery::normalize($gallery)->map(function (array $item) use ($coverPath) {
                return $this->serializeGalleryItem($item, $coverPath);
            })->values()->all();

            $result = [
                'success' => true,
                'message' => $kind === 'video' ? 'Đã thêm video vào thư viện.' : 'Đã thêm ảnh vào thư viện.',
                'remaining_count' => count($items),
                'cover_path' => $coverPath,
                'items' => $items,
            ];
            try {
                if (! $disk->put($completedPath, json_encode($result, JSON_THROW_ON_ERROR))) {
                    Log::warning('Gallery upload completed without a retry record', ['upload_id' => $validated['upload_id']]);
                }
            } catch (\Throwable $exception) {
                Log::warning('Gallery upload completed without a retry record', [
                    'upload_id' => $validated['upload_id'],
                    'error' => $exception->getMessage(),
                ]);
            }
            for ($index = 0; $index < $totalChunks; $index++) {
                $disk->delete("{$directory}/{$index}");
            }

            return response()->json($result);
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    private function galleryChunkDirectory(string $uploadId): string
    {
        $userId = (int) (auth()->id() ?: 0);

        return 'gallery-chunks/'.$userId.'/'.$uploadId;
    }

    private function assembleGalleryChunks(string $directory, int $totalChunks, string $originalName, string $kind): UploadedFile
    {
        $maxBytes = $kind === 'video' ? 50 * 1024 * 1024 : 999_999;
        $tmpPath = tempnam(sys_get_temp_dir(), 'gallery-chunk-');
        if ($tmpPath === false) {
            throw ValidationException::withMessages([
                'chunk' => 'Không tạo được file tạm để ghép phần tải lên.',
            ]);
        }

        $handle = fopen($tmpPath, 'wb');
        if ($handle === false) {
            @unlink($tmpPath);
            throw ValidationException::withMessages([
                'chunk' => 'Không ghi được file tạm.',
            ]);
        }

        try {
            $written = 0;
            for ($index = 0; $index < $totalChunks; $index++) {
                $path = $directory.'/'.$index;
                if (! Storage::disk('local')->exists($path)) {
                    throw ValidationException::withMessages([
                        'chunk' => 'Thiếu phần tải lên số '.($index + 1).'. Hãy thử lại.',
                    ]);
                }

                $bytes = Storage::disk('local')->get($path);
                $written += strlen((string) $bytes);
                if ($written > $maxBytes) {
                    throw ValidationException::withMessages([
                        'chunk' => $kind === 'video'
                            ? 'Video không được vượt quá 50MB.'
                            : 'Ảnh WebP sau khi tối ưu phải nhỏ hơn 1MB.',
                    ]);
                }

                fwrite($handle, (string) $bytes);
            }
        } catch (\Throwable $exception) {
            fclose($handle);
            @unlink($tmpPath);
            throw $exception;
        } finally {
            if (is_resource($handle)) {
                fclose($handle);
            }
        }

        $mime = (new SymfonyFile($tmpPath))->getMimeType() ?: 'application/octet-stream';

        return new UploadedFile($tmpPath, $originalName, $mime, null, true);
    }
}
