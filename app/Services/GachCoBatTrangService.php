<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\GachCoBatTrang;
use App\Models\GachCoBatTrangAnh;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class GachCoBatTrangService
{
    public function getFirstRecord(): GachCoBatTrang
    {
        return GachCoBatTrang::with('anh')->firstOrFail();
    }

    public function update(array $data): GachCoBatTrang
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $fillable = [];

            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $model->thumbnail_main, 'gach_co_bat_trang/images');
            }

            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            if (!empty($data['new_images']) && is_array($data['new_images'])) {
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $path = FileUploadHelper::upload($file, 'gach_co_bat_trang/gallery');
                        $model->anh()->create(['image' => $path]);
                    }
                }
            }

            if (!empty($data['cong_doan_images']) && is_array($data['cong_doan_images'])) {
                $currentImages = is_array($model->images) ? $model->images : [];
                foreach ($data['cong_doan_images'] as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $currentImages[] = \App\Helpers\FileUploadHelper::upload($file, 'gach_co_bat_trang/cong_doan_che_tac');
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

    public function deleteAnh(int $anhId): void
    {
        $anh = GachCoBatTrangAnh::findOrFail($anhId);
        FileUploadHelper::delete($anh->image);
        $anh->delete();
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