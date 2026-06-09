<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\GiaTriVuotTroi;
use Illuminate\Http\UploadedFile;

class GiaTriVuotTroiService
{
    public function getAll()
    {
        return GiaTriVuotTroi::query()->latest()->get();
    }

    public function findById(int $id): GiaTriVuotTroi
    {
        return GiaTriVuotTroi::query()->findOrFail($id);
    }

    public function addGiaTri(array $data): GiaTriVuotTroi
    {
        $imagePath = FileUploadHelper::upload($data['image'], 'gia_tri_vuot_troi/images');

        /** @var GiaTriVuotTroi $model */
        $model = GiaTriVuotTroi::query()->create([
            'title' => $data['title'],
            'desscription' => $data['desscription'],
            'image' => $imagePath,
        ]);

        return $model;
    }

    public function updateGiaTri(int $id, array $data): GiaTriVuotTroi
    {
        $giaTri = $this->findById($id);
        $fillable = [
            'title' => $data['title'] ?? $giaTri->title,
            'desscription' => $data['desscription'] ?? $giaTri->desscription,
        ];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $fillable['image'] = FileUploadHelper::replace($data['image'], $giaTri->image, 'gia_tri_vuot_troi/images');
        }

        $giaTri->fill($fillable)->save();

        return $giaTri->fresh();
    }

    public function deleteGiaTri(int $id): void
    {
        $giaTri = $this->findById($id);
        FileUploadHelper::delete($giaTri->image);

        GiaTriVuotTroi::destroy($id);
    }
}
