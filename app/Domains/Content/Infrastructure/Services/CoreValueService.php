<?php

namespace App\Domains\Content\Infrastructure\Services;

use App\Domains\Content\Infrastructure\Models\CoreValue;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class CoreValueService
{
    /** @return Collection<int, CoreValue> */
    public function getAll(): Collection
    {
        return CoreValue::query()->orderBy('gia_tri_vuot_troi_id')->get();
    }

    public function findById(int $id): CoreValue
    {
        return CoreValue::query()->findOrFail($id);
    }

    /** @param array<string, mixed> $data */
    public function addGiaTri(array $data): CoreValue
    {
        $imagePath = FileUploadHelper::upload($data['image'], 'gia_tri_vuot_troi/images');

        /** @var CoreValue $model */
        $model = CoreValue::query()->create([
            'title' => $data['title'],
            'desscription' => $data['desscription'],
            'image' => $imagePath,
        ]);

        return $model;
    }

    /** @param array<string, mixed> $data */
    public function updateGiaTri(int $id, array $data): CoreValue
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

        CoreValue::destroy($id);
    }
}
