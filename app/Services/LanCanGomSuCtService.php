<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\LanCanGomSuCt;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class LanCanGomSuCtService
{
    public function getAll(string $status = 'active')
    {
        $query = LanCanGomSuCt::query()
            ->with(['phanLoais' => fn ($q) => $q->where('is_delete', 0)])
            ->withCount(['phanLoais' => fn ($q) => $q->where('is_delete', 0)])
            ->latest();
        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }

        return $query->get();
    }

    public function findById(int $id): LanCanGomSuCt
    {
        return LanCanGomSuCt::query()
            ->with(['phanLoais' => fn ($q) => $q->where('is_delete', 0)])
            ->findOrFail($id);
    }

    public function create(array $data): LanCanGomSuCt
    {
        return DB::transaction(function () use ($data) {
            $fillable = [
                'name' => $data['name'], 'color' => trim((string) ($data['color'] ?? '')) ?: 'Tự chọn', 'size' => $data['size'] ?? null,
                'des' => ! empty($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
                'size_des' => ! empty($data['size_des']) ? array_values(array_filter(array_map('trim', $data['size_des']))) : null,
                'is_delete' => 0,
            ];
            $images = [];
            if (! empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $images[] = FileUploadHelper::upload($file, 'lan_can_gom_su_ct/images');
                    }
                }
            }
            $fillable['images'] = $images;
            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::upload($data['size_image'], 'lan_can_gom_su_ct/sizes');
            }

            return LanCanGomSuCt::create($fillable);
        });
    }

    public function update(int $id, array $data): LanCanGomSuCt
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable = [
                'name' => $data['name'], 'color' => trim((string) ($data['color'] ?? '')) ?: 'Tự chọn', 'size' => $data['size'] ?? $model->size,
                'des' => isset($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
                'size_des' => isset($data['size_des']) ? array_values(array_filter(array_map('trim', $data['size_des']))) : null,
            ];
            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::replace($data['size_image'], $model->size_image, 'lan_can_gom_su_ct/sizes');
            }
            if (! empty($data['new_images']) && is_array($data['new_images'])) {
                $currentImages = is_array($model->images) ? $model->images : [];
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $currentImages[] = FileUploadHelper::upload($file, 'lan_can_gom_su_ct/images');
                    }
                }
                $fillable['images'] = $currentImages;
            }
            $model->update($fillable);

            return $model->fresh();
        });
    }

    public function toggleStatus(int $id, int $status): void
    {
        $model = $this->findById($id);
        $model->update(['is_delete' => $status]);
        if ($status === 1) {
            $model->phanLoais()->update(['is_delete' => 1]);
        }
    }

    public function removeImageFromJson(int $id, string $imagePath): void
    {
        $model = $this->findById($id);
        $currentImages = is_array($model->images) ? $model->images : [];
        $newImages = array_filter($currentImages, fn ($path) => $path !== $imagePath);
        $model->update(['images' => empty($newImages) ? null : array_values($newImages)]);
        FileUploadHelper::delete($imagePath);
    }
}
