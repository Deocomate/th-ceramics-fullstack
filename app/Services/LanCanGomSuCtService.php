<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\LanCanGomSuCt;
use App\Services\Concerns\ManagesProductGalleryMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class LanCanGomSuCtService
{
    use ManagesProductGalleryMedia;

    private const IMAGE_DIRECTORY = 'lan_can_gom_su_ct/images';

    private const SIZE_DIRECTORY = 'lan_can_gom_su_ct/sizes';

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
                'video' => $this->normalizeJourneyVideo($data['video'] ?? null),
            ];
            $fillable['images'] = $this->composeGalleryPayload($data, self::IMAGE_DIRECTORY);
            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::upload($data['size_image'], self::SIZE_DIRECTORY);
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
                $fillable['size_image'] = FileUploadHelper::replace($data['size_image'], $model->size_image, self::SIZE_DIRECTORY);
            }
            $currentImages = is_array($model->images) ? $model->images : [];
            $fillable['video'] = $this->normalizeJourneyVideo($data['video'] ?? null);

            $merged = $this->mergeGalleryUpdates($currentImages, $data, self::IMAGE_DIRECTORY);
            if ($merged !== null) {
                $fillable['images'] = $merged;
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

    public function removeImageFromJson(int $id, string $imagePath): LanCanGomSuCt
    {
        return $this->removeGalleryImage($this->findById($id), $imagePath);
    }

    public function removeVideoFromJson(int $id, string $videoUrl): LanCanGomSuCt
    {
        return $this->removeGalleryVideo($this->findById($id), $videoUrl);
    }

    /**
     * @param  array<int, string>  $imagePaths
     * @param  array<int, string>  $videoUrls
     */
    public function removeGalleryItemsFromJson(int $id, array $imagePaths = [], array $videoUrls = []): LanCanGomSuCt
    {
        return $this->removeGalleryItems($this->findById($id), $imagePaths, $videoUrls);
    }

    public function appendImagesToGallery(int $id, array $files)
    {
        return $this->appendImagesToGalleryModel($this->findById($id), $files, self::IMAGE_DIRECTORY);
    }

    public function reorderGalleryItems(int $id, array $tokens)
    {
        return $this->reorderGalleryModel($this->findById($id), $tokens);
    }

    public function promoteCoverImage(int $id, string $imagePath)
    {
        return $this->promoteCoverOnModel($this->findById($id), $imagePath);
    }
}
