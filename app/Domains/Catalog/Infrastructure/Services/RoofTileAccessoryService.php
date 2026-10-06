<?php

namespace App\Domains\Catalog\Infrastructure\Services;

use App\Domains\Catalog\Infrastructure\Models\RoofTileAccessory;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class RoofTileAccessoryService
{
    public function getFirstRecord(): RoofTileAccessory
    {
        return RoofTileAccessory::firstOrFail();
    }

    public function update(array $data): RoofTileAccessory
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $fillable = [];

            foreach (['banner_text_1', 'banner_text_2', 'sec1_title', 'sec2_title'] as $field) {
                if (array_key_exists($field, $data)) {
                    $fillable[$field] = $data[$field];
                }
            }

            // Cập nhật ảnh chính
            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $model->thumbnail_main, 'phu_kien_ngoi/images');
            }

            if (isset($data['sec1_image']) && $data['sec1_image'] instanceof UploadedFile) {
                $fillable['sec1_image'] = FileUploadHelper::replace($data['sec1_image'], $model->sec1_image, 'phu_kien_ngoi/images');
            }

            if (isset($data['sec2_image']) && $data['sec2_image'] instanceof UploadedFile) {
                $fillable['sec2_image'] = FileUploadHelper::replace($data['sec2_image'], $model->sec2_image, 'phu_kien_ngoi/images');
            }

            // Gộp thêm ảnh mới vào mảng JSON hiện tại
            if (! empty($data['new_images']) && is_array($data['new_images'])) {
                $currentImages = is_array($model->images) ? $model->images : [];
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $currentImages[] = FileUploadHelper::upload($file, 'phu_kien_ngoi/gallery');
                    }
                }
                $fillable['images'] = $currentImages;
            }

            if (! empty($fillable)) {
                $model->update($fillable);
            }

            return $model->fresh();
        });
    }

    public function removeImageFromJson(string $imagePathToRemove): RoofTileAccessory
    {
        $model = $this->getFirstRecord();
        $currentImages = is_array($model->images) ? $model->images : [];

        $newImages = array_filter($currentImages, function ($path) use ($imagePathToRemove) {
            return $path !== $imagePathToRemove;
        });

        $newImages = array_values($newImages);
        $model->update(['images' => empty($newImages) ? null : $newImages]);

        FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }
}
