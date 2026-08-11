<?php

namespace App\Services\Concerns;

use App\Helpers\FileUploadHelper;
use App\Support\ProductGallery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

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
     * Build gallery from optional cover + gallery images + video URLs (create flow).
     *
     * @param  array<string, mixed>  $data
     * @return array<int, mixed>
     */
    protected function composeGalleryPayload(array $data, string $directory): array
    {
        $images = [];

        if (! empty($data['cover_image']) && $data['cover_image'] instanceof UploadedFile) {
            $images[] = FileUploadHelper::upload($data['cover_image'], $directory);
        }

        if (! empty($data['images']) && is_array($data['images'])) {
            $images = $this->appendGalleryImages($images, $data['images'], $directory);
        }

        if (! empty($data['video_urls']) && is_array($data['video_urls'])) {
            $images = $this->appendGalleryVideos($images, $data['video_urls']);
        }

        return $images;
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
        return $this->removeGalleryItems($model, [$imagePath], [], $imagesAttribute);
    }

    protected function removeGalleryVideo(Model $model, string $videoUrl, string $imagesAttribute = 'images'): Model
    {
        return $this->removeGalleryItems($model, [], [$videoUrl], $imagesAttribute);
    }

    /**
     * @param  array<int, mixed>  $imagePaths
     * @param  array<int, mixed>  $videoUrls
     */
    protected function removeGalleryItems(
        Model $model,
        array $imagePaths = [],
        array $videoUrls = [],
        string $imagesAttribute = 'images'
    ): Model {
        $current = is_array($model->{$imagesAttribute}) ? $model->{$imagesAttribute} : [];
        $paths = array_values(array_filter($imagePaths, fn ($path) => is_string($path) && $path !== ''));
        $urls = array_values(array_filter($videoUrls, fn ($url) => is_string($url) && $url !== ''));

        $updated = ProductGallery::removeImagePaths($current, $paths);
        $updated = ProductGallery::removeVideoUrls($updated, $urls);

        $model->update([
            $imagesAttribute => empty($updated) ? null : $updated,
        ]);

        foreach ($paths as $path) {
            FileUploadHelper::delete($path);
        }

        $model->refresh();

        return $model;
    }

    /**
     * @param  array<int, mixed>  $files
     */
    protected function appendImagesToGalleryModel(
        Model $model,
        array $files,
        string $directory,
        string $imagesAttribute = 'images'
    ): Model {
        $current = is_array($model->{$imagesAttribute}) ? $model->{$imagesAttribute} : [];
        $updated = $this->appendGalleryImages($current, $files, $directory);

        $model->update([
            $imagesAttribute => empty($updated) ? null : $updated,
        ]);
        $model->refresh();

        return $model;
    }

    /**
     * @param  array<int, mixed>  $tokens
     */
    protected function reorderGalleryModel(
        Model $model,
        array $tokens,
        string $imagesAttribute = 'images'
    ): Model {
        $current = is_array($model->{$imagesAttribute}) ? $model->{$imagesAttribute} : [];
        $updated = ProductGallery::reorderMedia($current, $tokens);

        $model->update([
            $imagesAttribute => empty($updated) ? null : $updated,
        ]);
        $model->refresh();

        return $model;
    }

    protected function promoteCoverOnModel(
        Model $model,
        string $imagePath,
        string $imagesAttribute = 'images'
    ): Model {
        $current = is_array($model->{$imagesAttribute}) ? $model->{$imagesAttribute} : [];
        $updated = ProductGallery::promoteImageToCover($current, $imagePath);

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

        if (! empty($data['cover_image']) && $data['cover_image'] instanceof UploadedFile) {
            $oldCover = ProductGallery::firstImagePath($updated);
            $newCoverPath = FileUploadHelper::upload($data['cover_image'], $imageDirectory);

            if ($oldCover) {
                $updated = ProductGallery::removeImagePath($updated, $oldCover);
                FileUploadHelper::delete($oldCover);
            }

            array_unshift($updated, $newCoverPath);
            $updated = array_values($updated);
            $changed = true;
        }

        if (! empty($data['gallery_order']) && is_array($data['gallery_order'])) {
            $updated = ProductGallery::reorderMedia($updated, $data['gallery_order']);
            $changed = true;
        }

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
