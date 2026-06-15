<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\PhuKienNgoiCt;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PhuKienNgoiCtService
{
    public function getAll(string $status = 'active', ?string $categoryType = null)
    {
        $query = PhuKienNgoiCt::query()
            ->with(['phanLoais' => fn ($q) => $q->where('is_delete', 0)->orderBy('price')])
            ->withCount(['phanLoais' => fn ($q) => $q->where('is_delete', 0)])
            ->latest();

        if ($categoryType) {
            $query->where('category_type', $categoryType);
        }

        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }

        return $query->get();
    }

    public function findById(int $id): PhuKienNgoiCt
    {
        return PhuKienNgoiCt::query()->findOrFail($id);
    }

    public function create(array $data): PhuKienNgoiCt
    {
        return DB::transaction(function () use ($data) {
            $fillable = [
                'name' => $data['name'],
                'category_type' => $data['category_type'],
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
                        $images[] = FileUploadHelper::upload($file, 'phu_kien_ngoi_ct/images');
                    }
                }
            }
            $fillable['images'] = $images;

            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::upload($data['size_image'], 'phu_kien_ngoi_ct/sizes');
            }

            return PhuKienNgoiCt::query()->create($fillable);
        });
    }

    public function update(int $id, array $data): PhuKienNgoiCt
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable = [
                'name' => $data['name'],
                'category_type' => $data['category_type'] ?? $model->category_type,
                'color' => trim((string) ($data['color'] ?? '')) ?: 'Tự chọn',
                'size' => $data['size'] ?? $model->size,
                'des' => isset($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
                'size_des' => isset($data['size_des']) ? array_values(array_filter(array_map('trim', $data['size_des']))) : null,
            ];

            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::replace($data['size_image'], $model->size_image, 'phu_kien_ngoi_ct/sizes');
            }

            if (! empty($data['new_images']) && is_array($data['new_images'])) {
                $currentImages = is_array($model->images) ? $model->images : [];
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $currentImages[] = FileUploadHelper::upload($file, 'phu_kien_ngoi_ct/images');
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

    public function removeImageFromJson(int $id, string $imagePathToRemove): PhuKienNgoiCt
    {
        $model = $this->findById($id);
        $currentImages = is_array($model->images) ? $model->images : [];

        $newImages = array_filter($currentImages, fn ($path) => $path !== $imagePathToRemove);

        $model->fill(['images' => empty($newImages) ? null : array_values($newImages)])->save();
        FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }
}
