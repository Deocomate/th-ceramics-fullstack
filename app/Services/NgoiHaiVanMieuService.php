<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\NgoiHaiVanMieu;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class NgoiHaiVanMieuService
{
    public function getFirstRecord(): NgoiHaiVanMieu
    {
        return NgoiHaiVanMieu::query()->firstOrFail();
    }

    public function update(array $data): NgoiHaiVanMieu
    {
        $ngoiHai = $this->getFirstRecord();

        return DB::transaction(function () use ($ngoiHai, $data) {
            $fillable = [
                'title1' => $data['title1'] ?? $ngoiHai->title1,
                'title2' => $data['title2'] ?? $ngoiHai->title2,
                'title3' => $data['title3'] ?? $ngoiHai->title3,
            ];

            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $ngoiHai->thumbnail_main, 'ngoi_hai_van_mieu/images');
            }
            if (isset($data['thumbnail1']) && $data['thumbnail1'] instanceof UploadedFile) {
                $fillable['thumbnail1'] = FileUploadHelper::replace($data['thumbnail1'], $ngoiHai->thumbnail1, 'ngoi_hai_van_mieu/images');
            }
            if (isset($data['thumbnail2']) && $data['thumbnail2'] instanceof UploadedFile) {
                $fillable['thumbnail2'] = FileUploadHelper::replace($data['thumbnail2'], $ngoiHai->thumbnail2, 'ngoi_hai_van_mieu/images');
            }
            if (isset($data['thumbnail3']) && $data['thumbnail3'] instanceof UploadedFile) {
                $fillable['thumbnail3'] = FileUploadHelper::replace($data['thumbnail3'], $ngoiHai->thumbnail3, 'ngoi_hai_van_mieu/images');
            }
            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            if (!empty($data['cong_doan_images']) && is_array($data['cong_doan_images'])) {
                $currentImages = is_array($ngoiHai->images) ? $ngoiHai->images : [];
                foreach ($data['cong_doan_images'] as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $currentImages[] = \App\Helpers\FileUploadHelper::upload($file, 'ngoi_hai_van_mieu/cong_doan_che_tac');
                    }
                }
                $fillable['images'] = $currentImages;
            }

            $ngoiHai->update($fillable);
            return $ngoiHai->fresh();
        });
    }

    public function removeImageFromJson(string $imagePathToRemove)
    {
        $model = $this->getFirstRecord();
        $currentImages = is_array($model->images) ? $model->images : [];

        $newImages = array_filter($currentImages, function ($path) use ($imagePathToRemove) {
            return $path !== $imagePathToRemove;
        });
        $newImages = array_values($newImages); // Reset index

        $model->update(['images' => empty($newImages) ? null : $newImages]);
        \App\Helpers\FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }
}
