<?php
namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\NgoiHaiVanMieuCt;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class NgoiHaiVanMieuCtService
{
    public function getAll(string $status = 'active')
    {
        $query = NgoiHaiVanMieuCt::query()->withCount(['mauSacs' => function($q) {
            $q->where('is_delete', 0);
        }])->latest();

        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }

        return $query->get();
    }

    public function findById(int $id): NgoiHaiVanMieuCt
    {
        return NgoiHaiVanMieuCt::query()->findOrFail($id);
    }

    public function create(array $data): NgoiHaiVanMieuCt
    {
        return DB::transaction(function () use ($data) {
            $fillable = [
                'name'       => $data['name'],
                'size'       => $data['size'] ?? null,
                'des'        => !empty($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
                'is_delete'  => 0,
                'price'      => 0, // Giá trị mặc định chống lỗi DB
                'mau_sac_id' => 0, // Giá trị mặc định chống lỗi DB
            ];

            $images =[];
            if (!empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $images[] = FileUploadHelper::upload($file, 'ngoi_hai_van_mieu_ct/images');
                    }
                }
            }
            $fillable['images'] = $images;

            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::upload($data['size_image'], 'ngoi_hai_van_mieu_ct/sizes');
            }

            return NgoiHaiVanMieuCt::query()->create($fillable);
        });
    }

    public function update(int $id, array $data): NgoiHaiVanMieuCt
    {
        /** @var NgoiHaiVanMieuCt $model */
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable = [
                'name' => $data['name'],
                'size' => $data['size'] ?? $model->size,
                'des'  => isset($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
            ];

            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::replace($data['size_image'], $model->size_image, 'ngoi_hai_van_mieu_ct/sizes');
            }

            if (!empty($data['new_images']) && is_array($data['new_images'])) {
                $currentImages = is_array($model->images) ? $model->images : [];
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $currentImages[] = FileUploadHelper::upload($file, 'ngoi_hai_van_mieu_ct/images');
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
        /** @var NgoiHaiVanMieuCt $model */
        $model = $this->findById($id);
        $model->fill(['is_delete' => $status])->save();

        // Ẩn luôn màu sắc con nếu cha bị ẩn
        if ($status === 1) {
            $model->mauSacs()->update(['is_delete' => 1]);
        }
    }

    public function removeImageFromJson(int $id, string $imagePathToRemove): NgoiHaiVanMieuCt
    {
        /** @var NgoiHaiVanMieuCt $model */
        $model = $this->findById($id);
        $currentImages = is_array($model->images) ? $model->images :[];

        $newImages = array_filter($currentImages, fn($path) => $path !== $imagePathToRemove);
        
        $model->fill(['images' => empty($newImages) ? null : array_values($newImages)])->save();
        FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }
}