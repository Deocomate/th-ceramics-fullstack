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
}