<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\DenGomSu;
use App\Models\DenGomSuAnh;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DenGomSuService
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return DenGomSu::with('anh')->latest()->paginate($perPage);
    }

    public function findById(int $id): DenGomSu
    {
        return DenGomSu::with('anh')->findOrFail($id);
    }

    public function create(array $data): DenGomSu
    {
        return DB::transaction(function () use ($data) {
            $thumbnailMain = FileUploadHelper::upload($data['thumbnail_main'], 'den_gom_su/images');
            $image1 = FileUploadHelper::upload($data['image1'], 'den_gom_su/images');
            $image2 = FileUploadHelper::upload($data['image2'], 'den_gom_su/images');
            $image3 = FileUploadHelper::upload($data['image3'], 'den_gom_su/images');
            $image4 = FileUploadHelper::upload($data['image4'], 'den_gom_su/images');

            return DenGomSu::create([
                'thumbnail_main' => $thumbnailMain,
                'video'          => $data['video'] ?? null,
                'image1'         => $image1,
                'image2'         => $image2,
                'title2'         => $data['title2'] ?? null,
                'image3'         => $image3,
                'title3'         => $data['title3'] ?? null,
                'image4'         => $image4,
            ]);
        });
    }

    public function update(int $id, array $data): DenGomSu
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable =[
                'title2' => $data['title2'] ?? $model->title2,
                'title3' => $data['title3'] ?? $model->title3,
            ];

            $imageFields =['thumbnail_main', 'image1', 'image2', 'image3', 'image4'];

            foreach ($imageFields as $field) {
                if (isset($data[$field]) && $data[$field] instanceof UploadedFile) {
                    $fillable[$field] = FileUploadHelper::replace($data[$field], $model->{$field}, 'den_gom_su/images');
                }
            }

            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            $model->update($fillable);

            return $model->fresh();
        });
    }

    public function delete(int $id): void
    {
        $model = $this->findById($id);

        DB::transaction(function () use ($model) {
            // Xóa file bảng gallery
            foreach ($model->anh as $anh) {
                FileUploadHelper::delete($anh->image);
            }

            // Xóa các file trực tiếp trên bảng
            $imageFields =['thumbnail_main', 'image1', 'image2', 'image3', 'image4'];
            foreach ($imageFields as $field) {
                FileUploadHelper::delete($model->{$field});
            }

            $model->delete();
        });
    }

    // --- THƯ VIỆN ẢNH ĐÈN GỐM SỨ ---

    public function addAnh(int $denId, array $data): DenGomSuAnh
    {
        $model = $this->findById($denId);
        $imagePath = FileUploadHelper::upload($data['image'], 'den_gom_su/gallery');

        return $model->anh()->create(['image' => $imagePath]);
    }

    public function deleteAnh(int $anhId): void
    {
        $anh = DenGomSuAnh::findOrFail($anhId);
        FileUploadHelper::delete($anh->image);
        $anh->delete();
    }
}