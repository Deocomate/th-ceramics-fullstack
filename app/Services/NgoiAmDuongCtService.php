<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\NgoiAmDuongCt;
use App\Services\Concerns\ManagesProductGalleryMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class NgoiAmDuongCtService
{
    use ManagesProductGalleryMedia;

    private const IMAGE_DIRECTORY = 'ngoi_am_duong_ct/images';

    private const SIZE_DIRECTORY = 'ngoi_am_duong_ct/sizes';

    public function __construct(
        private readonly GlobalProductCodeService $globalCodeService
    ) {}

    public function getAll(string $status = 'active')
    {
        $query = NgoiAmDuongCt::query()->latest();

        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }

        return $query->get();
    }

    public function findById(int $id): NgoiAmDuongCt
    {
        return NgoiAmDuongCt::findOrFail($id);
    }

    public function create(array $data): NgoiAmDuongCt
    {
        if (! $this->globalCodeService->isUnique($data['code'])) {
            throw new InvalidArgumentException('Mã sản phẩm (Code) đã tồn tại trên hệ thống.');
        }

        return DB::transaction(function () use ($data) {
            $fillable = [
                'code' => $data['code'],
                'name' => $data['name'],
                'color' => trim((string) ($data['color'] ?? '')) ?: 'Tự chọn',
                'price' => $data['price'],
                'size' => $data['size'] ?? null,
                'des' => ! empty($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
                'is_delete' => 0,
                'video' => $this->normalizeJourneyVideo($data['video'] ?? null),
            ];

            $fillable['images'] = $this->composeGalleryPayload($data, self::IMAGE_DIRECTORY);

            if (isset($data['size_image']) && $data['size_image'] instanceof UploadedFile) {
                $fillable['size_image'] = FileUploadHelper::upload($data['size_image'], self::SIZE_DIRECTORY);
            }

            return NgoiAmDuongCt::create($fillable);
        });
    }

    public function update(int $id, array $data): NgoiAmDuongCt
    {
        $model = $this->findById($id);

        if (! $this->globalCodeService->isUnique($data['code'], 'ngoi_am_duong_ct', $model->ngoi_am_duong_ct_id)) {
            throw new InvalidArgumentException('Mã sản phẩm (Code) này đã được sử dụng ở một sản phẩm khác.');
        }

        return DB::transaction(function () use ($model, $data) {
            $fillable = [
                'code' => $data['code'],
                'name' => $data['name'],
                'color' => trim((string) ($data['color'] ?? '')) ?: 'Tự chọn',
                'price' => $data['price'],
                'size' => $data['size'] ?? $model->size,
                'des' => isset($data['des']) ? array_values(array_filter(array_map('trim', $data['des']))) : null,
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
            $model->refresh();

            return $model;
        });
    }

    public function deleteProduct(int $id): void
    {
        $model = $this->findById($id);
        $model->update(['is_delete' => 1]);
    }

    public function restoreProduct(int $id): void
    {
        $model = $this->findById($id);
        $model->update(['is_delete' => 0]);
    }

    public function removeImageFromJson(int $id, string $imagePathToRemove): NgoiAmDuongCt
    {
        return $this->removeGalleryImage($this->findById($id), $imagePathToRemove);
    }

    public function removeVideoFromJson(int $id, string $videoUrl): NgoiAmDuongCt
    {
        return $this->removeGalleryVideo($this->findById($id), $videoUrl);
    }

    /**
     * @param  array<int, string>  $imagePaths
     * @param  array<int, string>  $videoUrls
     */
    public function removeGalleryItemsFromJson(int $id, array $imagePaths = [], array $videoUrls = []): NgoiAmDuongCt
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
