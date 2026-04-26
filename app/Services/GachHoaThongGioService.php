<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\GachHoaThongGio;
use App\Models\GachHoaThongGioAnh;
use App\Models\GiaTriGachHoaThongGio;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class GachHoaThongGioService
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return GachHoaThongGio::with(['anh', 'giaTri'])->latest()->paginate($perPage);
    }

    public function findById(int $id): GachHoaThongGio
    {
        return GachHoaThongGio::with(['anh', 'giaTri'])->findOrFail($id);
    }

    public function create(array $data): GachHoaThongGio
    {
        return DB::transaction(function () use ($data) {
            $image = FileUploadHelper::upload($data['image'], 'gach_hoa_thong_gio/images');

            return GachHoaThongGio::create([
                'image' => $image,
                'video' => $data['video'] ?? null,
            ]);
        });
    }

    public function update(int $id, array $data): GachHoaThongGio
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable =[];

            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $fillable['image'] = FileUploadHelper::replace($data['image'], $model->image, 'gach_hoa_thong_gio/images');
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
            // Xóa file ảnh thư viện
            foreach ($model->anh as $anh) {
                FileUploadHelper::delete($anh->image);
            }

            // Xóa file ảnh giá trị
            foreach ($model->giaTri as $giaTri) {
                FileUploadHelper::delete($giaTri->image);
                FileUploadHelper::delete($giaTri->background);
            }

            // Xóa ảnh chính
            FileUploadHelper::delete($model->image);

            $model->delete();
        });
    }

    // --- Xử lý Bảng Phụ: Ảnh ---

    public function addAnh(int $gachId, array $data): GachHoaThongGioAnh
    {
        $model = $this->findById($gachId);
        $imagePath = FileUploadHelper::upload($data['image'], 'gach_hoa_thong_gio/gallery');

        return $model->anh()->create(['image' => $imagePath]);
    }

    public function deleteAnh(int $anhId): void
    {
        $anh = GachHoaThongGioAnh::findOrFail($anhId);
        FileUploadHelper::delete($anh->image);
        $anh->delete();
    }

    // --- Xử lý Bảng Phụ: Giá Trị ---

    public function addGiaTri(int $gachId, array $data): GiaTriGachHoaThongGio
    {
        $model = $this->findById($gachId);
        
        $backgroundPath = FileUploadHelper::upload($data['background'], 'gach_hoa_thong_gio/gia_tri');
        $imagePath = FileUploadHelper::upload($data['image'], 'gach_hoa_thong_gio/gia_tri');

        return $model->giaTri()->create([
            'background'   => $backgroundPath,
            'image'        => $imagePath,
            'title'        => $data['title'],
            'desscription' => $data['desscription'],
        ]);
    }

    public function updateGiaTri(int $giaTriId, array $data): GiaTriGachHoaThongGio
    {
        $giaTri = GiaTriGachHoaThongGio::findOrFail($giaTriId);
        
        $fillable =[
            'title'        => $data['title'] ?? $giaTri->title,
            'desscription' => $data['desscription'] ?? $giaTri->desscription,
        ];

        if (isset($data['background']) && $data['background'] instanceof UploadedFile) {
            $fillable['background'] = FileUploadHelper::replace($data['background'], $giaTri->background, 'gach_hoa_thong_gio/gia_tri');
        }

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $fillable['image'] = FileUploadHelper::replace($data['image'], $giaTri->image, 'gach_hoa_thong_gio/gia_tri');
        }

        $giaTri->update($fillable);

        return $giaTri->fresh();
    }

    public function deleteGiaTri(int $giaTriId): void
    {
        $giaTri = GiaTriGachHoaThongGio::findOrFail($giaTriId);
        FileUploadHelper::delete($giaTri->background);
        FileUploadHelper::delete($giaTri->image);
        $giaTri->delete();
    }
}