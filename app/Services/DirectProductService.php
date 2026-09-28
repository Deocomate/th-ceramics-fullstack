<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Services\Concerns\ManagesProductGalleryMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

abstract class DirectProductService
{
    use ManagesProductGalleryMedia;

    protected const TYPE = '';

    protected const MODEL = Model::class;

    protected const HAS_SIZE_DESCRIPTION = false;

    public function __construct(private readonly GlobalProductCodeService $globalCodeService) {}

    public function getAll(string $status = 'active')
    {
        if (config('product_catalog.read_unified')) {
            return app(UnifiedProductCatalog::class)->all(static::TYPE, $status);
        }
        $model = static::MODEL;
        $query = $model::query()->orderedByPriority();
        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }

        return $query->get();
    }

    public function findById(int $id): Model
    {
        if (config('product_catalog.read_unified')) {
            return app(UnifiedProductCatalog::class)->find(static::TYPE, $id);
        }
        $model = static::MODEL;

        return $model::findOrFail($id);
    }

    public function create(array $data): Model
    {
        if (! $this->globalCodeService->isUnique($data['code'])) {
            throw new InvalidArgumentException('Mã sản phẩm (Code) đã tồn tại trên hệ thống.');
        }

        return DB::transaction(function () use ($data) {
            $values = [
                'code' => $data['code'],
                'name' => $data['name'],
                'color' => trim((string) ($data['color'] ?? '')) ?: 'Tự chọn',
                'price' => $data['price'],
                'size' => $data['size'] ?? null,
                'des' => ! empty($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
                'is_delete' => 0,
                'video' => $this->normalizeJourneyVideo($data['video'] ?? null),
                'images' => $this->composeGalleryPayload($data, $this->imageDirectory()),
            ];
            if (static::HAS_SIZE_DESCRIPTION) {
                $values['size_des'] = ! empty($data['size_des'])
                    ? array_values(array_filter(array_map('trim', $data['size_des']))) : null;
            }
            if (($data['size_image'] ?? null) instanceof UploadedFile) {
                $values['size_image'] = FileUploadHelper::upload($data['size_image'], $this->sizeDirectory());
            }
            $model = static::MODEL;

            return $model::create($values);
        });
    }

    public function update(int $id, array $data): Model
    {
        $model = $this->findById($id);
        $primaryKey = $model->getKeyName();
        if (! $this->globalCodeService->isUnique($data['code'], static::TYPE, (int) $model->{$primaryKey})) {
            throw new InvalidArgumentException('Mã sản phẩm (Code) này đã được sử dụng ở một sản phẩm khác.');
        }

        return DB::transaction(function () use ($model, $data) {
            $values = [
                'code' => $data['code'],
                'name' => $data['name'],
                'color' => trim((string) ($data['color'] ?? '')) ?: 'Tự chọn',
                'price' => $data['price'],
                'size' => $data['size'] ?? $model->size,
                'des' => isset($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
                'video' => $this->normalizeJourneyVideo($data['video'] ?? null),
            ];
            if (static::HAS_SIZE_DESCRIPTION) {
                $values['size_des'] = isset($data['size_des'])
                    ? array_values(array_filter(array_map('trim', $data['size_des']))) : null;
            }
            if (($data['size_image'] ?? null) instanceof UploadedFile) {
                $values['size_image'] = FileUploadHelper::replace($data['size_image'], $model->size_image, $this->sizeDirectory());
            }
            $merged = $this->mergeGalleryUpdates(is_array($model->images) ? $model->images : [], $data, $this->imageDirectory());
            if ($merged !== null) {
                $values['images'] = $merged;
            }
            $model->update($values);

            return $model->fresh();
        });
    }

    public function toggleStatus(int $id, int $status): void
    {
        $this->findById($id)->update(['is_delete' => $status]);
    }

    public function deleteProduct(int $id): void
    {
        $this->toggleStatus($id, 1);
    }

    public function restoreProduct(int $id): void
    {
        $this->toggleStatus($id, 0);
    }

    public function removeImageFromJson(int $id, string $imagePathToRemove): Model
    {
        return $this->removeGalleryImage($this->findById($id), $imagePathToRemove);
    }

    public function removeVideoFromJson(int $id, string $videoUrl): Model
    {
        return $this->removeGalleryVideo($this->findById($id), $videoUrl);
    }

    public function removeGalleryItemsFromJson(int $id, array $imagePaths = [], array $videoUrls = [], array $videoPaths = []): Model
    {
        return $this->removeGalleryItems($this->findById($id), $imagePaths, $videoUrls, $videoPaths);
    }

    public function appendImagesToGallery(int $id, array $files): Model
    {
        return $this->appendImagesToGalleryModel($this->findById($id), $files, $this->imageDirectory());
    }

    public function appendMediaToGallery(int $id, array $images = [], array $videoUrls = [], array $videoFiles = []): Model
    {
        return $this->appendMediaToGalleryModel($this->findById($id), $images, $videoUrls, $videoFiles, $this->imageDirectory());
    }

    public function reorderGalleryItems(int $id, array $tokens): Model
    {
        return $this->reorderGalleryModel($this->findById($id), $tokens);
    }

    public function promoteCoverImage(int $id, string $imagePath): Model
    {
        return $this->promoteCoverOnModel($this->findById($id), $imagePath);
    }

    private function imageDirectory(): string
    {
        return static::TYPE.'/images';
    }

    private function sizeDirectory(): string
    {
        return static::TYPE.'/sizes';
    }
}
