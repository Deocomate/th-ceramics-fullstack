<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\DenGomSu;
use App\Models\DenGomSuAnh;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class DenGomSuService
{
    public function getFirstRecord(): DenGomSu
    {
        return DenGomSu::with('anh')->firstOrFail();
    }

    public function update(array $data): DenGomSu
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $fillable = [
                'title2' => $data['title2'] ?? $model->title2,
                'title3' => $data['title3'] ?? $model->title3,
            ];

            $imageFields = ['thumbnail_main', 'image1', 'image2', 'image3', 'image4'];

            foreach ($imageFields as $field) {
                if (isset($data[$field]) && $data[$field] instanceof UploadedFile) {
                    $fillable[$field] = FileUploadHelper::replace($data[$field], $model->{$field}, 'den_gom_su/images');
                }
            }

            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            if (! empty($data['new_images']) && is_array($data['new_images'])) {
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $path = FileUploadHelper::upload($file, 'den_gom_su/gallery');
                        $model->anh()->create(['image' => $path]);
                    }
                }
            }

            $model->update($fillable);

            return $model->fresh();
        });
    }

    public function deleteAnh(int $anhId): void
    {
        $anh = DenGomSuAnh::findOrFail($anhId);
        FileUploadHelper::delete($anh->image);
        $anh->delete();
    }
}
