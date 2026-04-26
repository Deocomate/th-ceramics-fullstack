<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\GiaTriLanCanGomXu;
use App\Models\LanCanGomXu;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LanCanGomXuService
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return LanCanGomXu::with('giaTri')->latest()->paginate($perPage);
    }

    public function findById(int $id): LanCanGomXu
    {
        return LanCanGomXu::with('giaTri')->findOrFail($id);
    }

    public function create(array $data): LanCanGomXu
    {
        return DB::transaction(function () use ($data) {
            $thumbnailMain = FileUploadHelper::upload($data['thumbnail_main'], 'lan_can_gom_xu/images');

            return LanCanGomXu::create([
                'thumbnail_main' => $thumbnailMain,
                'video'          => $data['video'] ?? null,
            ]);
        });
    }

    public function update(int $id, array $data): LanCanGomXu
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable =[];

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

    public function delete(int $id): void
    {
        $model = $this->findById($id);

        DB::transaction(function () use ($model) {
            // Xóa file bảng phụ
            foreach ($model->giaTri as $giaTri) {
                FileUploadHelper::delete($giaTri->image);
            }

            // Xóa file chính
            FileUploadHelper::delete($model->thumbnail_main);
            
            $model->delete();
        });
    }

    // --- GIÁ TRỊ LAN CAN GỐM XỨ ---

    public function addGiaTri(int $lanCanId, array $data): GiaTriLanCanGomXu
    {
        $model = $this->findById($lanCanId);
        
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