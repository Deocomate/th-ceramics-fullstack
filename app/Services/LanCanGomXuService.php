<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\GiaTriLanCanGomXu;
use App\Models\LanCanGomXu;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class LanCanGomXuService
{
    public function getFirstRecord(): LanCanGomXu
    {
        return LanCanGomXu::with('giaTri')->firstOrFail();
    }

    public function update(array $data): LanCanGomXu
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $fillable = [];

            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $model->thumbnail_main, 'lan_can_gom_xu/images');
            }

            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            if (!empty($fillable)) {
                $model->update($fillable);
            }

            return $model->fresh();
        });
    }

    public function addGiaTri(array $data): GiaTriLanCanGomXu
    {
        $model = $this->getFirstRecord();
        
        $imagePath = FileUploadHelper::upload($data['image'], 'lan_can_gom_xu/gia_tri');

        return $model->giaTri()->create([
            'image'        => $imagePath,
            'title'        => $data['title'],
            'desscription' => $data['desscription'],
        ]);
    }

    public function updateGiaTri(int $giaTriId, array $data): GiaTriLanCanGomXu
    {
        $giaTri = GiaTriLanCanGomXu::findOrFail($giaTriId);
        
        $fillable =[
            'title'        => $data['title'] ?? $giaTri->title,
            'desscription' => $data['desscription'] ?? $giaTri->desscription,
        ];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $fillable['image'] = FileUploadHelper::replace($data['image'], $giaTri->image, 'lan_can_gom_xu/gia_tri');
        }

        $giaTri->update($fillable);

        return $giaTri->fresh();
    }

    public function deleteGiaTri(int $giaTriId): void
    {
        $giaTri = GiaTriLanCanGomXu::findOrFail($giaTriId);
        FileUploadHelper::delete($giaTri->image);
        $giaTri->delete();
    }
}