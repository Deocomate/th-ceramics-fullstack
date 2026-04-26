<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\PhuKienNgoi;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PhuKienNgoiService
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return PhuKienNgoi::latest()->paginate($perPage);
    }

    public function findById(int $id): PhuKienNgoi
    {
        return PhuKienNgoi::findOrFail($id);
    }

    /**
     * Tham số images là mảng các file UploadedFile
     */
    public function create(array $data): PhuKienNgoi
    {
        return DB::transaction(function () use ($data) {
            $thumbnailMain = FileUploadHelper::upload($data['thumbnail_main'], 'phu_kien_ngoi/images');
            
            $imagesArray =[];
            if (!empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $imagesArray[] = FileUploadHelper::upload($file, 'phu_kien_ngoi/gallery');
                    }
                }
            }

            return PhuKienNgoi::create([
                'thumbnail_main' => $thumbnailMain,
                'images'         => !empty($imagesArray) ? $imagesArray : null,
            ]);
        });
    }

    public function update(int $id, array $data): PhuKienNgoi
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable =[];

            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $model->thumbnail_main, 'phu_kien_ngoi/images');
            }

            // Xử lý upload thêm ảnh vào mảng JSON hiện tại
            if (!empty($data['new_images']) && is_array($data['new_images'])) {
                $currentImages = is_array($model->images) ? $model->images :[];
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $currentImages[] = FileUploadHelper::upload($file, 'phu_kien_ngoi/gallery');
                    }
                }
                $fillable['images'] = $currentImages;
            }

            if (!empty($fillable)) {
                $model->update($fillable);
            }

            return $model->fresh();
        });
    }

    /**
     * Xóa một file cụ thể khỏi mảng JSON
     */
    public function removeImageFromJson(int $id, string $imagePathToRemove): PhuKienNgoi
    {
        $model = $this->findById($id);
        $currentImages = is_array($model->images) ? $model->images :[];

        $newImages = array_filter($currentImages, function ($path) use ($imagePathToRemove) {
            return $path !== $imagePathToRemove;
        });

        // Cập nhật lại array keys
        $newImages = array_values($newImages);
        $model->update(['images' => empty($newImages) ? null : $newImages]);

        // Xóa file vật lý
        FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }

    public function delete(int $id): void
    {
        $model = $this->findById($id);

        DB::transaction(function () use ($model) {
            FileUploadHelper::delete($model->thumbnail_main);

            if (is_array($model->images)) {
                foreach ($model->images as $imagePath) {
                    FileUploadHelper::delete($imagePath);
                }
            }

            $model->delete();
        });
    }
}