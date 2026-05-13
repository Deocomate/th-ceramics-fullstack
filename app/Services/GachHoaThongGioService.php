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
            $fillable = [];

            if (isset($data['video_thumbnail']) && $data['video_thumbnail'] instanceof UploadedFile) {
                $fillable['video_thumbnail'] = FileUploadHelper::replace(
                    $data['video_thumbnail'],
                    $model->video_thumbnail,
                    'gach_hoa_thong_gio/video_thumbnail'
                );
            }

            if (array_key_exists('video_url', $data)) {
                $fillable['video_url'] = $data['video_url'];
            }

            if (!empty($data['new_images']) && is_array($data['new_images'])) {
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $path = FileUploadHelper::upload($file, 'gach_hoa_thong_gio/gallery');
                        $model->anh()->create(['image' => $path]);
                    }
                }
            }

            if (!empty($data['process_images']) && is_array($data['process_images'])) {
                $currentImages = is_array($model->process_images) ? $model->process_images : [];
                foreach ($data['process_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $currentImages[] = FileUploadHelper::upload($file, 'gach_hoa_thong_gio/cong_doan_che_tac');
                    }
                }
                $fillable['process_images'] = $currentImages;
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
        
        $imagePath = FileUploadHelper::upload($data['image'], 'gach_hoa_thong_gio/gia_tri');

        return $model->giaTri()->create([
            'background'   => $data['background'],
            'image'        => $imagePath,
            'title'        => $data['title'],
            'desscription' => $data['desscription'], // Theo đúng tên trong DB
        ]);
    }

    public function updateGiaTri(int $giaTriId, array $data): GiaTriGachHoaThongGio
    {
        $giaTri = GiaTriGachHoaThongGio::findOrFail($giaTriId);
        
        $fillable = [
            'title'        => $data['title'] ?? $giaTri->title,
            'desscription' => $data['desscription'] ?? $giaTri->desscription,
            'background'   => $data['background'] ?? $giaTri->background,
        ];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $fillable['image'] = FileUploadHelper::replace($data['image'], $giaTri->image, 'gach_hoa_thong_gio/gia_tri');
        }

        $giaTri->update($fillable);

        return $giaTri->fresh();
    }

    public function deleteGiaTri(int $giaTriId): void
    {
        $giaTri = GiaTriGachHoaThongGio::findOrFail($giaTriId);
        FileUploadHelper::delete($giaTri->image);
        $giaTri->delete();
    }

    public function removeProcessImage(string $imagePathToRemove): GachHoaThongGio
    {
        $model = $this->getFirstRecord();
        $currentImages = is_array($model->process_images) ? $model->process_images : [];

        $newImages = array_filter($currentImages, function ($path) use ($imagePathToRemove) {
            return $path !== $imagePathToRemove;
        });
        $newImages = array_values($newImages); // Reset index

        $model->update(['process_images' => empty($newImages) ? null : $newImages]);
        FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }
}
