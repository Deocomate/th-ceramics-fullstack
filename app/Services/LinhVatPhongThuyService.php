<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\LinhVat;
use App\Models\LinhVatPhongThuy;
use App\Models\LinhVatPhongThuyAnh;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LinhVatPhongThuyService
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return LinhVatPhongThuy::with(['linhVat', 'anh'])->latest()->paginate($perPage);
    }

    public function findById(int $id): LinhVatPhongThuy
    {
        return LinhVatPhongThuy::with(['linhVat', 'anh'])->findOrFail($id);
    }

    public function create(array $data): LinhVatPhongThuy
    {
        return DB::transaction(function () use ($data) {
            $thumbnailMain = FileUploadHelper::upload($data['thumbnail_main'], 'linh_vat_phong_thuy/images');

            return LinhVatPhongThuy::create([
                'thumbnail_main' => $thumbnailMain,
                'video'          => $data['video'] ?? null,
            ]);
        });
    }

    public function update(int $id, array $data): LinhVatPhongThuy
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable = [];

            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $model->thumbnail_main, 'linh_vat_phong_thuy/images');
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
            // Xóa file chi tiết Linh Vật
            foreach ($model->linhVat as $item) {
                FileUploadHelper::delete($item->image);
            }

            // Xóa file thư viện ảnh
            foreach ($model->anh as $anh) {
                FileUploadHelper::delete($anh->image);
            }

            // Xóa ảnh chính
            FileUploadHelper::delete($model->thumbnail_main);
            $model->delete();
        });
    }

    // --- BẢNG PHỤ: LINH VẬT CHI TIẾT ---

    public function addLinhVat(int $parentId, array $data): LinhVat
    {
        $model = $this->findById($parentId);
        $imagePath = FileUploadHelper::upload($data['image'], 'linh_vat_phong_thuy/items');

        return $model->linhVat()->create([
            'image'       => $imagePath,
            'title'       => $data['title'],
            'description' => $data['description'],
        ]);
    }

    public function updateLinhVat(int $itemId, array $data): LinhVat
    {
        $item = LinhVat::findOrFail($itemId);

        $fillable = [
            'title'       => $data['title'] ?? $item->title,
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

    // --- BẢNG PHỤ: THƯ VIỆN ẢNH ---

    public function addAnh(int $parentId, array $data): LinhVatPhongThuyAnh
    {
        $model = $this->findById($parentId);
        $imagePath = FileUploadHelper::upload($data['image'], 'linh_vat_phong_thuy/gallery');

        return $model->anh()->create(['image' => $imagePath]);
    }

    public function deleteAnh(int $anhId): void
    {
        $anh = LinhVatPhongThuyAnh::findOrFail($anhId);
        FileUploadHelper::delete($anh->image);
        $anh->delete();
    }
}