<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\PhuKienNgoiCt;
use App\Services\Concerns\ManagesProductGalleryMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PhuKienNgoiCtService
{
    use ManagesProductGalleryMedia;

    private const IMAGE_DIRECTORY = 'phu_kien_ngoi_ct/images';

    private const SIZE_DIRECTORY = 'phu_kien_ngoi_ct/sizes';

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
                'video' => $this->normalizeJourneyVideo($data['video'] ?? null),
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
                $fillable['size_image'] = FileUploadHelper::replace($data['size_image'], $model->size_image, self::SIZE_DIRECTORY);
            }

            $currentImages = is_array($model->images) ? $model->images : [];
            $fillable['video'] = $this->normalizeJourneyVideo($data['video'] ?? null);

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
        $model = $this->findById($id);
        $model->fill(['is_delete' => $status])->save();

        if ($status === 1) {
            $model->phanLoais()->update(['is_delete' => 1]);
        }
    }

    public function removeImageFromJson(int $id, string $imagePathToRemove): PhuKienNgoiCt
    {
        return $this->removeGalleryImage($this->findById($id), $imagePathToRemove);
    }

    public function removeVideoFromJson(int $id, string $videoUrl): PhuKienNgoiCt
    {
        return $this->removeGalleryVideo($this->findById($id), $videoUrl);
    }

    /**
     * @param  array<int, string>  $imagePaths
     * @param  array<int, string>  $videoUrls
     */
    public function removeGalleryItemsFromJson(int $id, array $imagePaths = [], array $videoUrls = []): PhuKienNgoiCt
    {
        return $this->removeGalleryItems($this->findById($id), $imagePaths, $videoUrls);
    }
}
