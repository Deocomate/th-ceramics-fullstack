<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Support\ProductGallery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait DestroysProductGalleryMedia
{
    /**
     * @param  callable(array<int, string> $imagePaths, array<int, string> $videoUrls, array<int, string> $videoPaths): Model  $remover
     */
    protected function destroyGalleryMediaResponse(Request $request, callable $remover): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'image_path' => ['nullable', 'string'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'video_path' => ['nullable', 'string'],
            'image_paths' => ['nullable', 'array'],
            'image_paths.*' => ['string'],
            'video_urls' => ['nullable', 'array'],
            'video_urls.*' => ['string', 'max:500'],
            'video_paths' => ['nullable', 'array'],
            'video_paths.*' => ['string'],
        ]);

        $imagePaths = array_values(array_unique(array_filter(array_merge(
            filled($validated['image_path'] ?? null) ? [(string) $validated['image_path']] : [],
            $validated['image_paths'] ?? []
        ), fn ($path) => is_string($path) && $path !== '')));

        $videoUrls = array_values(array_unique(array_filter(array_merge(
            filled($validated['video_url'] ?? null) ? [(string) $validated['video_url']] : [],
            $validated['video_urls'] ?? []
        ), fn ($url) => is_string($url) && $url !== '')));

        $videoPaths = array_values(array_unique(array_filter(array_merge(
            filled($validated['video_path'] ?? null) ? [(string) $validated['video_path']] : [],
            $validated['video_paths'] ?? []
        ), fn ($path) => is_string($path) && $path !== '')));

        if ($imagePaths === [] && $videoUrls === [] && $videoPaths === []) {
            throw ValidationException::withMessages([
                'image_path' => 'Chọn ít nhất một ảnh hoặc video để xóa.',
            ]);
        }

        $model = $remover($imagePaths, $videoUrls, $videoPaths);
        $remaining = ProductGallery::normalize($model->images ?? [])->count();
        $deletedCount = count($imagePaths) + count($videoUrls) + count($videoPaths);

        $message = $deletedCount > 1
            ? "Đã xóa {$deletedCount} mục khỏi thư viện media."
            : (count($videoUrls) === 1 || count($videoPaths) === 1
                ? 'Đã xóa video khỏi sản phẩm.'
                : 'Đã xóa ảnh khỏi sản phẩm.');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'remaining_count' => $remaining,
            ]);
        }

        return back()->with('success', $message);
    }
}
