<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Support\ProductGallery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait UploadsProductGalleryMedia
{
    /**
     * @param  callable(array<int, mixed> $files): Model  $appender
     */
    protected function storeGalleryImagesResponse(Request $request, callable $appender): JsonResponse
    {
        $validated = $request->validate([
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'images.required' => 'Vui lòng chọn ít nhất một ảnh.',
            'images.max' => 'Mỗi lần chỉ tải tối đa 10 ảnh.',
            'images.*.file' => 'File tải lên phải là file hợp lệ.',
            'images.*.uploaded' => 'Một ảnh tải lên thất bại (thường do file quá lớn hoặc lỗi webp). Thử lại từng ảnh hoặc chuyển sang jpg/png.',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png, webp.',
            'images.*.max' => 'Mỗi hình ảnh không được vượt quá 5MB.',
        ]);

        $model = $appender($validated['images']);
        $gallery = is_array($model->images) ? $model->images : [];
        $coverPath = ProductGallery::firstImagePath($gallery);

        $items = ProductGallery::normalize($gallery)->map(function (array $item) use ($coverPath) {
            $token = ($item['type'] ?? '') === ProductGallery::TYPE_VIDEO
                ? 'video:'.($item['url'] ?? '')
                : 'image:'.($item['path'] ?? '');

            $payload = [
                'type' => $item['type'],
                'token' => $token,
                'is_cover' => ($item['type'] ?? '') === ProductGallery::TYPE_IMAGE
                    && ($item['path'] ?? null) === $coverPath,
            ];

            if (($item['type'] ?? '') === ProductGallery::TYPE_VIDEO) {
                $payload['url'] = $item['url'] ?? null;
                $payload['embed_url'] = $item['embed_url'] ?? null;
                $payload['thumb_url'] = $item['thumb_url'] ?? null;
            } else {
                $path = $item['path'] ?? '';
                $payload['path'] = $path;
                $payload['url'] = $path !== '' ? Storage::disk('public')->url($path) : null;
            }

            return $payload;
        })->values()->all();

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm ảnh vào thư viện.',
            'remaining_count' => count($items),
            'cover_path' => $coverPath,
            'items' => $items,
        ]);
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
