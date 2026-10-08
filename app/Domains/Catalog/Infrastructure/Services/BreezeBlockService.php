<?php

namespace App\Domains\Catalog\Infrastructure\Services;

use App\Domains\Catalog\Infrastructure\Models\AttributeValueBreezeBlock;
use App\Domains\Catalog\Infrastructure\Models\BreezeBlock;
use App\Domains\Catalog\Infrastructure\Models\BreezeBlockImage;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class BreezeBlockService
{
    public function getFirstRecord(): BreezeBlock
    {
        return BreezeBlock::with(['anh', 'giaTri'])->firstOrFail();
    }

    public function update(array $data): BreezeBlock
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $fillable = [];

            if (isset($data['video_thumbnail']) && $data['video_thumbnail'] instanceof UploadedFile) {
                $fillable['video_thumbnail'] = FileUploadHelper::replace(
                    $data['video_thumbnail'],
                    $model->video_thumbnail,
                    'gach_hoa_thong_gio/video_thumbnail'
                );
            }

            if (array_key_exists('video_url', $data)) {
                $fillable['video_url'] = $data['video_url'];
            }

            if (! empty($data['new_images']) && is_array($data['new_images'])) {
                foreach ($data['new_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $path = FileUploadHelper::upload($file, 'gach_hoa_thong_gio/gallery');
                        $model->anh()->create(['image' => $path]);
                    }
                }
            }

            if (! empty($data['process_images']) && is_array($data['process_images'])) {
                $currentImages = is_array($model->process_images) ? $model->process_images : [];
                foreach ($data['process_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $currentImages[] = FileUploadHelper::upload($file, 'gach_hoa_thong_gio/cong_doan_che_tac');
                    }
                }
                $fillable['process_images'] = $currentImages;
            }

            if (! empty($fillable)) {
                $model->update($fillable);
            }

            return $model->fresh();
        });
    }

    public function addAnh(array $data): BreezeBlockImage
    {
        $model = $this->getFirstRecord();
        $imagePath = FileUploadHelper::upload($data['image'], 'gach_hoa_thong_gio/gallery');

        return $model->anh()->create(['image' => $imagePath]);
    }

    public function deleteAnh(int $anhId): void
    {
        $anh = BreezeBlockImage::findOrFail($anhId);
        FileUploadHelper::delete($anh->image);
        $anh->delete();
    }

    public function addGiaTri(array $data): AttributeValueBreezeBlock
    {
        $model = $this->getFirstRecord();

        $imagePath = FileUploadHelper::upload($data['image'], 'gach_hoa_thong_gio/gia_tri');

        return $model->giaTri()->create([
            'background' => $data['background'],
            'image' => $imagePath,
            'title' => $data['title'],
            'desscription' => $data['desscription'],
        ]);
    }

    public function updateGiaTri(int $giaTriId, array $data): AttributeValueBreezeBlock
    {
        $giaTri = AttributeValueBreezeBlock::findOrFail($giaTriId);

        $fillable = [
            'title' => $data['title'] ?? $giaTri->title,
            'desscription' => $data['desscription'] ?? $giaTri->desscription,
            'background' => $data['background'] ?? $giaTri->background,
        ];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $fillable['image'] = FileUploadHelper::replace($data['image'], $giaTri->image, 'gach_hoa_thong_gio/gia_tri');
        }

        $giaTri->update($fillable);

        return $giaTri->fresh();
    }

    public function deleteGiaTri(int $giaTriId): void
    {
        $giaTri = AttributeValueBreezeBlock::findOrFail($giaTriId);
        FileUploadHelper::delete($giaTri->image);
        $giaTri->delete();
    }

    public function removeProcessImage(string $imagePathToRemove): BreezeBlock
    {
        $model = $this->getFirstRecord();
        $currentImages = is_array($model->process_images) ? $model->process_images : [];

        $newImages = array_filter($currentImages, function ($path) use ($imagePathToRemove) {
            return $path !== $imagePathToRemove;
        });
        $newImages = array_values($newImages);

        $model->update(['process_images' => empty($newImages) ? null : $newImages]);
        FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }
}
