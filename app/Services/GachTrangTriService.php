<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\DauAnGachTrangTri;
use App\Models\GachTrangTri;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class GachTrangTriService
{
    public function getFirstRecord(): GachTrangTri
    {
        return GachTrangTri::with('dauAn')->firstOrFail();
    }

    public function update(array $data): GachTrangTri
    {
        $model = $this->getFirstRecord();
        
        return DB::transaction(function () use ($model, $data) {
            $fillable =[];
            
            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $model->thumbnail_main, 'gach_trang_tri/images');
            }
            
            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }
            
            if (!empty($data['cong_doan_images']) && is_array($data['cong_doan_images'])) {
                $currentImages = is_array($model->images) ? $model->images :[];
                foreach ($data['cong_doan_images'] as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $currentImages[] = \App\Helpers\FileUploadHelper::upload($file, 'gach_trang_tri/cong_doan_che_tac');
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

    public function addDauAn(array $data): DauAnGachTrangTri
    {
        $model = $this->getFirstRecord();

        $backgroundPath = FileUploadHelper::upload($data['background'], 'gach_trang_tri/dau_an');

        return $model->dauAn()->create([
            'background'  => $backgroundPath,
            'title'       => $data['title'],
            'location'    => $data['location'],
            'description' => $data['description'],
        ]);
    }

    public function updateDauAn(int $dauAnId, array $data): DauAnGachTrangTri
    {
        $dauAn = DauAnGachTrangTri::findOrFail($dauAnId);

        $fillable = [
            'title'       => $data['title'] ?? $dauAn->title,
            'location'    => $data['location'] ?? $dauAn->location,
            'description' => $data['description'] ?? $dauAn->description,
        ];

        if (isset($data['background']) && $data['background'] instanceof UploadedFile) {
            $fillable['background'] = FileUploadHelper::replace($data['background'], $dauAn->background, 'gach_trang_tri/dau_an');
        }

        $dauAn->update($fillable);

        return $dauAn->fresh();
    }

    public function deleteDauAn(int $dauAnId): void
    {
        $dauAn = DauAnGachTrangTri::findOrFail($dauAnId);
        FileUploadHelper::delete($dauAn->background);
        $dauAn->delete();
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
