<?php

namespace App\Services\Concerns;

use App\Helpers\FileUploadHelper;
use App\Support\ProductGallery;
use Illuminate\Database\Eloquent\Model;

trait ManagesProductGalleryMedia
{
    /**
     * @param  array<int, mixed>  $files
     * @return array<int, mixed>
     */
    protected function storeGalleryImages(array $files, string $directory): array
    {
        return ProductGallery::appendUploadedImages([], $files, $directory);
    }

    /**
     * @param  array<int, mixed>  $current
     * @param  array<int, mixed>  $files
     * @return array<int, mixed>
     */
    protected function appendGalleryImages(array $current, array $files, string $directory): array
    {
        return ProductGallery::appendUploadedImages($current, $files, $directory);
    }

    /**
     * @param  array<int, mixed>  $current
     * @param  array<int, mixed>  $urls
     * @return array<int, mixed>
     */
    protected function appendGalleryVideos(array $current, array $urls): array
    {
        return ProductGallery::appendVideoUrls($current, $urls);
    }

    protected function normalizeJourneyVideo(mixed $video): ?string
    {
        if (! is_string($video)) {
            return null;
        }

        $trimmed = trim($video);

        return $trimmed !== '' ? $trimmed : null;
    }

    protected function removeGalleryImage(Model $model, string $imagePath, string $imagesAttribute = 'images'): Model
    {
        $current = is_array($model->{$imagesAttribute}) ? $model->{$imagesAttribute} : [];
        $updated = ProductGallery::removeImagePath($current, $imagePath);

        $model->update([
            $imagesAttribute => empty($updated) ? null : $updated,
        ]);

        FileUploadHelper::delete($imagePath);
        $model->refresh();

        return $model;
    }

    protected function removeGalleryVideo(Model $model, string $videoUrl, string $imagesAttribute = 'images'): Model
    {
        $current = is_array($model->{$imagesAttribute}) ? $model->{$imagesAttribute} : [];
        $updated = ProductGallery::removeVideoUrl($current, $videoUrl);

        $model->update([
            $imagesAttribute => empty($updated) ? null : $updated,
        ]);

        $model->refresh();

        return $model;
    }

    /**
     * @param  array<int, mixed>  $current
     * @param  array<string, mixed>  $data
     * @return array<int, mixed>|null
     */
    protected function mergeGalleryUpdates(array $current, array $data, string $imageDirectory): ?array
    {
        $updated = $current;
        $changed = false;

        if (! empty($data['new_images']) && is_array($data['new_images'])) {
            $updated = $this->appendGalleryImages($updated, $data['new_images'], $imageDirectory);
            $changed = true;
        }

        $videoUrls = $data['new_video_urls'] ?? $data['video_urls'] ?? null;
        if (! empty($videoUrls) && is_array($videoUrls)) {
            $updated = $this->appendGalleryVideos($updated, $videoUrls);
            $changed = true;
        }

        return $changed ? $updated : null;
    }
}
