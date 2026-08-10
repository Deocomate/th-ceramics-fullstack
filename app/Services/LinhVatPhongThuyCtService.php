<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\LinhVatPhongThuyCt;
use App\Services\Concerns\ManagesProductGalleryMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class LinhVatPhongThuyCtService
{
    use ManagesProductGalleryMedia;

    private const IMAGE_DIRECTORY = 'linh_vat_phong_thuy_ct/images';

    private const SIZE_DIRECTORY = 'linh_vat_phong_thuy_ct/sizes';

    public function __construct(private readonly GlobalProductCodeService $globalCodeService) {}

    public function getAll(string $status = 'active')
    {
        $query = LinhVatPhongThuyCt::query()->latest();

        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }

        return $query->get();
    }

    public function findById(int $id): LinhVatPhongThuyCt
    {
        return LinhVatPhongThuyCt::findOrFail($id);
    }

    public function create(array $data): LinhVatPhongThuyCt
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

            return LinhVatPhongThuyCt::create($fillable);
        });
    }

    public function update(int $id, array $data): LinhVatPhongThuyCt
    {
        $model = $this->findById($id);

        if (! $this->globalCodeService->isUnique($data['code'], 'linh_vat_phong_thuy_ct', $model->linh_vat_phong_thuy_ct_id)) {
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
    }

    public function removeImageFromJson(int $id, string $imagePathToRemove): LinhVatPhongThuyCt
    {
        return $this->removeGalleryImage($this->findById($id), $imagePathToRemove);
    }

    public function removeVideoFromJson(int $id, string $videoUrl): LinhVatPhongThuyCt
    {
        return $this->removeGalleryVideo($this->findById($id), $videoUrl);
    }
}
