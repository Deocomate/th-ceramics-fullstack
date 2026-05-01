<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\PhuKienNgoi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PhuKienNgoiService
{
    public function getFirstRecord(): PhuKienNgoi
    {
        return PhuKienNgoi::firstOrFail();
    }

    public function update(array $data): PhuKienNgoi
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $fillable =[];

            // Cập nhật ảnh chính
            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $model->thumbnail_main, 'phu_kien_ngoi/images');
            }

            // Gộp thêm ảnh mới vào mảng JSON hiện tại
            if (!empty($data['new_images']) && is_array($data['new_images'])) {
                $currentImages = is_array($model->images) ? $model->images :[];
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $currentImages[] = FileUploadHelper::upload($file, 'phu_kien_ngoi/gallery');
                    }
                }
                $fillable['images'] = $currentImages;
            }

            if (!empty($fillable)) {
                $model->update($fillable);
            }

            return $model->fresh();
        });
    }

    public function removeImageFromJson(string $imagePathToRemove): PhuKienNgoi
    {
        $model = $this->getFirstRecord();
        $currentImages = is_array($model->images) ? $model->images :[];

        $newImages = array_filter($currentImages, function ($path) use ($imagePathToRemove) {
            return $path !== $imagePathToRemove;
        });

        $newImages = array_values($newImages); // Reset array keys
        $model->update(['images' => empty($newImages) ? null : $newImages]);

        FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }
}