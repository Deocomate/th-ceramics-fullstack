<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\BoNocChuVanCt;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class BoNocChuVanCtService
{
    public function getAll(string $status = 'active')
    {
        $query = BoNocChuVanCt::query()->withCount(['phanLoais' => function ($q) {
            $q->where('is_delete', 0);
        }])->latest();

        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }

        return $query->get();
    }

    public function findById(int $id): BoNocChuVanCt
    {
        return BoNocChuVanCt::query()->findOrFail($id);
    }

    public function create(array $data): BoNocChuVanCt
    {
        return DB::transaction(function () use ($data) {
            $fillable = [
                'name' => $data['name'],
                'color' => trim((string) ($data['color'] ?? '')) ?: 'Tự chọn',
                'size' => $data['size'] ?? null,
                'des' => ! empty($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
                'size_des' => ! empty($data['size_des']) ? array_values(array_filter(array_map('trim', $data['size_des']))) : null,
                'is_delete' => 0,
            ];

            $images = [];
            if (! empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $images[] = FileUploadHelper::upload($file, 'bo_noc_chu_van_ct/images');
                    }
                }
            }
            $fillable['images'] = $images;

            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::upload($data['size_image'], 'bo_noc_chu_van_ct/sizes');
            }

            return BoNocChuVanCt::query()->create($fillable);
        });
    }

    public function update(int $id, array $data): BoNocChuVanCt
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable = [
                'name' => $data['name'],
                'color' => trim((string) ($data['color'] ?? '')) ?: 'Tự chọn',
                'size' => $data['size'] ?? $model->size,
                'des' => isset($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
                'size_des' => isset($data['size_des']) ? array_values(array_filter(array_map('trim', $data['size_des']))) : null,
            ];

            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::replace($data['size_image'], $model->size_image, 'bo_noc_chu_van_ct/sizes');
            }

            if (! empty($data['new_images']) && is_array($data['new_images'])) {
                $currentImages = is_array($model->images) ? $model->images : [];
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $currentImages[] = FileUploadHelper::upload($file, 'bo_noc_chu_van_ct/images');
                    }
                }
                $fillable['images'] = $currentImages;
            }

            $model->fill($fillable)->save();

            return $model->fresh();
        });
    }

    public function toggleStatus(int $id, int $status): void
    {
        $model = $this->findById($id);
        $model->fill(['is_delete' => $status])->save();

        if ($status === 1) {
            $model->phanLoais()->update(['is_delete' => 1]);
        }
    }

    public function removeImageFromJson(int $id, string $imagePathToRemove): BoNocChuVanCt
    {
        $model = $this->findById($id);
        $currentImages = is_array($model->images) ? $model->images : [];

        $newImages = array_filter($currentImages, fn ($path) => $path !== $imagePathToRemove);

        $model->fill(['images' => empty($newImages) ? null : array_values($newImages)])->save();
        FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }
}
