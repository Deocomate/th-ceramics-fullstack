<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\NgoiHaiVanMieuCt;
use App\Services\Concerns\ManagesProductGalleryMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class NgoiHaiVanMieuCtService
{
    use ManagesProductGalleryMedia;

    private const IMAGE_DIRECTORY = 'ngoi_hai_van_mieu_ct/images';

    private const SIZE_DIRECTORY = 'ngoi_hai_van_mieu_ct/sizes';

    public function getAll(string $status = 'active')
    {
        $query = NgoiHaiVanMieuCt::query()->withCount(['mauSacs' => function ($q) {
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
                'name' => $data['name'],
                'color' => trim((string) ($data['color'] ?? '')) ?: 'Tự chọn',
                'size' => $data['size'] ?? null,
                'des' => ! empty($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
                'is_delete' => 0,
                'price' => 0, // Giá trị mặc định chống lỗi DB
                'mau_sac_id' => 0, // Giá trị mặc định chống lỗi DB
            ];

            $images = [];
            if (! empty($data['images']) && is_array($data['images'])) {
                $images = $this->storeGalleryImages($data['images'], self::IMAGE_DIRECTORY);
            }
            if (! empty($data['video_urls']) && is_array($data['video_urls'])) {
                $images = $this->appendGalleryVideos($images, $data['video_urls']);
            }
            $fillable['images'] = $images;

            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::upload($data['size_image'], self::SIZE_DIRECTORY);
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
                'color' => trim((string) ($data['color'] ?? '')) ?: 'Tự chọn',
                'size' => $data['size'] ?? $model->size,
                'des' => isset($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
            ];

            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::replace($data['size_image'], $model->size_image, self::SIZE_DIRECTORY);
            }

            $currentImages = is_array($model->images) ? $model->images : [];
            $merged = $this->mergeGalleryUpdates($currentImages, $data, self::IMAGE_DIRECTORY);
            if ($merged !== null) {
                $fillable['images'] = $merged;
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
        return $this->removeGalleryImage($this->findById($id), $imagePathToRemove);
    }

    public function removeVideoFromJson(int $id, string $videoUrl): NgoiHaiVanMieuCt
    {
        return $this->removeGalleryVideo($this->findById($id), $videoUrl);
    }
}
