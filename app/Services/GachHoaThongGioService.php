<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\GachHoaThongGio;
use App\Models\GachHoaThongGioAnh;
use App\Models\GiaTriGachHoaThongGio;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class GachHoaThongGioService
{
    public function getFirstRecord(): GachHoaThongGio
    {
        return GachHoaThongGio::with(['anh', 'giaTri'])->firstOrFail();
    }

    public function update(array $data): GachHoaThongGio
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $fillable =[];

            // 1. Cập nhật Background
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $fillable['image'] = FileUploadHelper::replace($data['image'], $model->image, 'gach_hoa_thong_gio/images');
            }

            // 2. Cập nhật Video
            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            // 3. Upload thêm Thư viện ảnh
            if (!empty($data['new_images']) && is_array($data['new_images'])) {
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $path = FileUploadHelper::upload($file, 'gach_hoa_thong_gio/gallery');
                        $model->anh()->create(['image' => $path]);
                    }
                }
            }

            if (!empty($fillable)) {
                $model->update($fillable);
            }

            return $model->fresh();
        });
    }

    // --- Xử lý Bảng Phụ: Ảnh ---

    public function addAnh(array $data): GachHoaThongGioAnh
    {
        $model = $this->getFirstRecord();
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

    public function addGiaTri(array $data): GiaTriGachHoaThongGio
    {
        $model = $this->getFirstRecord();
        
        $backgroundPath = FileUploadHelper::upload($data['background'], 'gach_hoa_thong_gio/gia_tri');
        $imagePath = FileUploadHelper::upload($data['image'], 'gach_hoa_thong_gio/gia_tri');

        return $model->giaTri()->create([
            'background'   => $backgroundPath,
            'image'        => $imagePath,
            'title'        => $data['title'],
            'desscription' => $data['desscription'], // Theo đúng tên trong DB
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