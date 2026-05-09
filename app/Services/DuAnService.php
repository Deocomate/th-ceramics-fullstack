<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\DuAn;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DuAnService
{
    public function getAll(?int $danhMucId = null)
    {
        $query = DuAn::query()->with('danhMuc')->latest();

        if ($danhMucId) {
            $query->where('danh_muc_du_an_id', $danhMucId);
        }

        return $query->get();
    }

    public function findById(int $id): DuAn
    {
        return DuAn::findOrFail($id);
    }

    public function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (DuAn::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('du_an_id', '!=', $ignoreId))->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function create(array $data): DuAn
    {
        return DB::transaction(function () use ($data) {
            $fillable = [
                'ten_du_an' => $data['ten_du_an'],
                'dia_diem' => $data['dia_diem'],
                'san_pham' => $data['san_pham'],
                'nam' => $data['nam'] ?? null,
                'danh_muc_du_an_id' => $data['danh_muc_du_an_id'],
                'slug' => $this->generateUniqueSlug($data['ten_du_an']),
            ];

            $images = [];
            if (!empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $images[] = FileUploadHelper::upload($file, 'du_an/images');
                    }
                }
            }
            $fillable['images'] = $images;

            return DuAn::create($fillable);
        });
    }

    public function update(int $id, array $data): DuAn
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable =[
                'ten_du_an' => $data['ten_du_an'],
                'dia_diem' => $data['dia_diem'],
                'san_pham' => $data['san_pham'],
                'nam' => $data['nam'] ?? $model->nam,
                'danh_muc_du_an_id' => $data['danh_muc_du_an_id'],
            ];

            // Cập nhật lại slug nếu đổi tên
            if ($model->ten_du_an !== $data['ten_du_an']) {
                $fillable['slug'] = $this->generateUniqueSlug($data['ten_du_an'], $model->du_an_id);
            }

            if (!empty($data['new_images']) && is_array($data['new_images'])) {
                $currentImages = is_array($model->images) ? $model->images : [];
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $currentImages[] = FileUploadHelper::upload($file, 'du_an/images');
                    }
                }
                $fillable['images'] = $currentImages;
            }

            $model->update($fillable);
            return $model->fresh();
        });
    }

    public function destroy(int $id): void
    {
        $model = $this->findById($id);
        
        if (is_array($model->images)) {
            foreach ($model->images as $img) {
                FileUploadHelper::delete($img);
            }
        }
        
        $model->delete();
    }

    public function removeImageFromJson(int $id, string $imagePathToRemove): DuAn
    {
        $model = $this->findById($id);
        $currentImages = is_array($model->images) ? $model->images :[];

        $newImages = array_filter($currentImages, fn($path) => $path !== $imagePathToRemove);
        $model->update(['images' => empty($newImages) ? null : array_values($newImages)]);
        FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }
}