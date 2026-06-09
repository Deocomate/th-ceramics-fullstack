<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\LinhVat;
use App\Models\LinhVatPhongThuy;
use App\Models\LinhVatPhongThuyAnh;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class LinhVatPhongThuyService
{
    public function getFirstRecord(): LinhVatPhongThuy
    {
        return LinhVatPhongThuy::with(['linhVat', 'anh'])->firstOrFail();
    }

    public function update(array $data): LinhVatPhongThuy
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $fillable = [];

            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $model->thumbnail_main, 'linh_vat_phong_thuy/images');
            }

            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            if (! empty($data['new_images']) && is_array($data['new_images'])) {
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $path = FileUploadHelper::upload($file, 'linh_vat_phong_thuy/gallery');
                        $model->anh()->create(['image' => $path]);
                    }
                }
            }

            if (! empty($fillable)) {
                $model->update($fillable);
            }

            return $model->fresh();
        });
    }

    public function addLinhVat(array $data): LinhVat
    {
        $model = $this->getFirstRecord();
        $imagePath = FileUploadHelper::upload($data['image'], 'linh_vat_phong_thuy/items');

        return $model->linhVat()->create([
            'image' => $imagePath,
            'title' => $data['title'],
            'description' => $data['description'],
        ]);
    }

    public function updateLinhVat(int $itemId, array $data): LinhVat
    {
        $item = LinhVat::findOrFail($itemId);

        $fillable = [
            'title' => $data['title'] ?? $item->title,
            'description' => $data['description'] ?? $item->description,
        ];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $fillable['image'] = FileUploadHelper::replace($data['image'], $item->image, 'linh_vat_phong_thuy/items');
        }

        $item->update($fillable);

        return $item->fresh();
    }

    public function deleteLinhVat(int $itemId): void
    {
        $item = LinhVat::findOrFail($itemId);
        FileUploadHelper::delete($item->image);
        $item->delete();
    }

    public function deleteAnh(int $anhId): void
    {
        $anh = LinhVatPhongThuyAnh::findOrFail($anhId);
        FileUploadHelper::delete($anh->image);
        $anh->delete();
    }
}
