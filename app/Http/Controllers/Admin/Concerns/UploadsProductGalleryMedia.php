<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Rules\YoutubeUrl;
use App\Support\AssetPath;
use App\Support\ProductGallery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

trait UploadsProductGalleryMedia
{
    use StoresChunkedGalleryUploads;

    /**
     * @param  callable(array<int, mixed> $images, array<int, string> $videoUrls, array<int, mixed> $videoFiles): Model  $appender
     */
    protected function storeGalleryImagesResponse(Request $request, callable $appender): JsonResponse
    {
        if ($request->hasFile('chunk')) {
            return $this->storeGalleryChunkResponse($request, $appender);
        }

        $validated = $request->validate([
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video_urls' => ['nullable', 'array', 'max:20'],
            'video_urls.*' => ['nullable', 'string', 'max:500', 'url', new YoutubeUrl],
            'videos' => ['nullable', 'array', 'max:5'],
            'videos.*' => ['file', 'mimes:mp4,webm', 'max:51200'],
        ], [
            'images.max' => 'Mỗi lần chỉ tải tối đa 10 ảnh.',
            'images.*.file' => 'File tải lên phải là file hợp lệ.',
            'images.*.uploaded' => 'Một ảnh tải lên thất bại (thường do file quá lớn hoặc lỗi webp). Thử lại từng ảnh hoặc chuyển sang jpg/png.',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png, webp.',
            'images.*.max' => 'Mỗi hình ảnh không được vượt quá 5MB.',
            'video_urls.*.url' => 'Link YouTube không hợp lệ.',
            'videos.max' => 'Mỗi lần chỉ tải tối đa 5 video.',
            'videos.*.file' => 'File video tải lên phải là file hợp lệ.',
            'videos.*.uploaded' => 'Video tải lên thất bại. File có thể quá lớn — tối đa 50MB.',
            'videos.*.mimes' => 'Video phải có định dạng mp4 hoặc webm.',
            'videos.*.max' => 'Mỗi video không được vượt quá 50MB.',
        ]);

        $images = array_values($validated['images'] ?? []);
        $videoUrls = array_values(array_filter(
            $validated['video_urls'] ?? [],
            fn ($url) => is_string($url) && trim($url) !== ''
        ));
        $videoFiles = array_values($validated['videos'] ?? []);

        if ($images === [] && $videoUrls === [] && $videoFiles === []) {
            throw ValidationException::withMessages([
                'images' => 'Vui lòng chọn ít nhất một ảnh, video file, hoặc link YouTube.',
            ]);
        }

        $model = $appender($images, $videoUrls, $videoFiles);
        $gallery = is_array($model->images) ? $model->images : [];
        $coverPath = ProductGallery::firstImagePath($gallery);

        $items = ProductGallery::normalize($gallery)->map(function (array $item) use ($coverPath) {
            return $this->serializeGalleryItem($item, $coverPath);
        })->values()->all();

        $added = count($images) + count($videoUrls) + count($videoFiles);
        $message = $added === 1 && $videoFiles !== []
            ? 'Đã thêm video vào thư viện.'
            : ($added === 1 && $videoUrls !== []
                ? 'Đã thêm video YouTube vào thư viện.'
                : 'Đã thêm media vào thư viện.');

        return response()->json([
            'success' => true,
            'message' => $message,
            'remaining_count' => count($items),
            'cover_path' => $coverPath,
            'items' => $items,
        ]);
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    protected function serializeGalleryItem(array $item, ?string $coverPath): array
    {
        $token = ProductGallery::mediaToken($item);
        $isFileVideo = ProductGallery::isFileVideo($item);
        $isYoutube = ($item['type'] ?? '') === ProductGallery::TYPE_VIDEO && ! $isFileVideo;

        $payload = [
            'type' => $item['type'],
            'source' => $item['source'] ?? ($isYoutube ? ProductGallery::SOURCE_YOUTUBE : null),
            'token' => $token,
            'is_cover' => ($item['type'] ?? '') === ProductGallery::TYPE_IMAGE
                && ($item['path'] ?? null) === $coverPath,
        ];

        if ($isFileVideo) {
            $path = $item['path'] ?? '';
            $payload['path'] = $path;
            $payload['url'] = $item['display_url'] ?? AssetPath::url($path);
            $payload['display_url'] = $payload['url'];

            return $payload;
        }

        if ($isYoutube) {
            $payload['url'] = $item['url'] ?? null;
            $payload['embed_url'] = $item['embed_url'] ?? null;
            $payload['thumb_url'] = $item['thumb_url'] ?? null;

            return $payload;
        }

        $path = $item['path'] ?? '';
        $payload['path'] = $path;
        $payload['url'] = $path !== '' ? Storage::disk('public')->url($path) : null;

        return $payload;
    }

    /**
     * @param  callable(array<int, string> $tokens): Model  $reordering
     * @param  callable(string $imagePath): Model|null  $promoting
     */
    protected function reorderGalleryResponse(Request $request, callable $reordering, ?callable $promoting = null): JsonResponse
    {
        $validated = $request->validate([
            'items' => ['nullable', 'array'],
            'items.*' => ['string'],
            'cover_path' => ['nullable', 'string'],
        ]);

        if (! empty($validated['cover_path']) && $promoting) {
            $model = $promoting($validated['cover_path']);
        } elseif (! empty($validated['items'])) {
            $model = $reordering(array_values($validated['items']));
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Thiếu dữ liệu sắp xếp hoặc ảnh bìa.',
            ], 422);
        }

        $gallery = is_array($model->images) ? $model->images : [];
        $coverPath = ProductGallery::firstImagePath($gallery);

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật thứ tự thư viện media.',
            'remaining_count' => ProductGallery::normalize($gallery)->count(),
            'cover_path' => $coverPath,
        ]);
    }
}
