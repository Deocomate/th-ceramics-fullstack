<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Support\ProductGallery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
            'total_chunks' => ['required', 'integer', 'min:1', 'max:60'],
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
        $allowed = $kind === 'video' ? ['mp4', 'webm'] : ['jpg', 'jpeg', 'png', 'webp'];
        if (! in_array($extension, $allowed, true)) {
            throw ValidationException::withMessages([
                'original_name' => $kind === 'video'
                    ? 'Video phải có định dạng mp4 hoặc webm.'
                    : 'Hình ảnh phải có định dạng: jpg, jpeg, png, webp.',
            ]);
        }

        $directory = $this->galleryChunkDirectory((string) $validated['upload_id']);
        Storage::disk('local')->putFileAs($directory, $validated['chunk'], (string) $chunkIndex);

        if ($chunkIndex < $totalChunks - 1) {
            return response()->json([
                'success' => true,
                'received' => $chunkIndex + 1,
                'total_chunks' => $totalChunks,
            ]);
        }

        $assembled = $this->assembleGalleryChunks($directory, $totalChunks, $originalName, $kind);

        try {
            $images = $kind === 'image' ? [$assembled] : [];
            $videos = $kind === 'video' ? [$assembled] : [];
            $model = $appender($images, [], $videos);
        } finally {
            Storage::disk('local')->deleteDirectory($directory);
            if (is_file($assembled->getPathname())) {
                @unlink($assembled->getPathname());
            }
        }

        $gallery = is_array($model->images) ? $model->images : [];
        $coverPath = ProductGallery::firstImagePath($gallery);
        $items = ProductGallery::normalize($gallery)->map(function (array $item) use ($coverPath) {
            return $this->serializeGalleryItem($item, $coverPath);
        })->values()->all();

        return response()->json([
            'success' => true,
            'message' => $kind === 'video' ? 'Đã thêm video vào thư viện.' : 'Đã thêm ảnh vào thư viện.',
            'remaining_count' => count($items),
            'cover_path' => $coverPath,
            'items' => $items,
        ]);
    }

    private function galleryChunkDirectory(string $uploadId): string
    {
        $userId = (int) (auth()->id() ?: 0);

        return 'gallery-chunks/'.$userId.'/'.$uploadId;
    }

    private function assembleGalleryChunks(string $directory, int $totalChunks, string $originalName, string $kind): UploadedFile
    {
        $maxBytes = $kind === 'video' ? 50 * 1024 * 1024 : 5 * 1024 * 1024;
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
                            : 'Mỗi hình ảnh không được vượt quá 5MB.',
                    ]);
                }

                fwrite($handle, (string) $bytes);
            }
        } finally {
            fclose($handle);
        }

        $mime = (new SymfonyFile($tmpPath))->getMimeType() ?: 'application/octet-stream';

        return new UploadedFile($tmpPath, $originalName, $mime, null, true);
    }
}
