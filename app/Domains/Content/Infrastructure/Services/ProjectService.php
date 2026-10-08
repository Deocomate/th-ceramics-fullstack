<?php

namespace App\Domains\Content\Infrastructure\Services;

use App\Domains\Content\Infrastructure\Models\Project;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectService
{
    public function getAll(?int $danhMucId = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Project::query()->with('category')->latest();

        if ($danhMucId) {
            $query->where('danh_muc_du_an_id', $danhMucId);
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): Project
    {
        return Project::findOrFail($id);
    }

    public function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (Project::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('du_an_id', '!=', $ignoreId))->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): Project
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
            if (! empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $images[] = FileUploadHelper::upload($file, 'du_an/images');
                    }
                }
            }
            $fillable['images'] = $images;

            return Project::create($fillable);
        });
    }

    /** @param array<string, mixed> $data */
    public function update(int $id, array $data): Project
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable = [
                'ten_du_an' => $data['ten_du_an'],
                'dia_diem' => $data['dia_diem'],
                'san_pham' => $data['san_pham'],
                'nam' => $data['nam'] ?? $model->nam,
                'danh_muc_du_an_id' => $data['danh_muc_du_an_id'],
            ];

            if ($model->ten_du_an !== $data['ten_du_an']) {
                $fillable['slug'] = $this->generateUniqueSlug($data['ten_du_an'], $model->du_an_id);
            }

            if (! empty($data['new_images']) && is_array($data['new_images'])) {
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

    public function removeImageFromJson(int $id, string $imagePathToRemove): Project
    {
        $model = $this->findById($id);
        $currentImages = is_array($model->images) ? $model->images : [];

        $newImages = array_filter($currentImages, fn ($path) => $path !== $imagePathToRemove);
        $model->update(['images' => empty($newImages) ? null : array_values($newImages)]);
        FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }
}
