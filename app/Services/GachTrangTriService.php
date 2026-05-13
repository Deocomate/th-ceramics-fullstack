<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\GachTrangTri;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class GachTrangTriService
{
    private const APPLICATION_SLOTS = ['main', 'sub_1', 'sub_2', 'sub_3', 'sub_4'];

    public function getFirstRecord(): GachTrangTri
    {
        return GachTrangTri::query()->firstOrFail();
    }

    public function update(array $data): GachTrangTri
    {
        $model = $this->getFirstRecord();
        
        return DB::transaction(function () use ($model, $data) {
            $fillable =[];
            
            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $model->thumbnail_main, 'gach_trang_tri/images');
            }
            
            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            $currentImages = is_array($model->images) ? $model->images : [];
            $imagesChanged = false;

            if (!empty($data['cong_doan_order']) && is_array($data['cong_doan_order'])) {
                $orderedImages = array_values(array_filter(
                    $data['cong_doan_order'],
                    fn ($path) => is_string($path) && in_array($path, $currentImages, true)
                ));
                $missingImages = array_values(array_diff($currentImages, $orderedImages));
                $currentImages = array_merge($orderedImages, $missingImages);
                $imagesChanged = true;
            }
            
            if (!empty($data['cong_doan_images']) && is_array($data['cong_doan_images'])) {
                foreach ($data['cong_doan_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $currentImages[] = FileUploadHelper::upload($file, 'gach_trang_tri/cong_doan_che_tac');
                        $imagesChanged = true;
                    }
                }
            }

            if ($imagesChanged) {
                $fillable['images'] = $currentImages;
            }

            if (array_key_exists('ung_dung_da_dang', $data) && is_array($data['ung_dung_da_dang'])) {
                $fillable['ung_dung_da_dang'] = $this->buildUngDungDaDangPayload(
                    $model->ung_dung_da_dang,
                    $data['ung_dung_da_dang']
                );
            }

            if (!empty($fillable)) {
                $model->update($fillable);
            }

            return $model->fresh();
        });
    }

    public function removeImageFromJson(string $imagePathToRemove)
    {
        $model = $this->getFirstRecord();
        $currentImages = is_array($model->images) ? $model->images : [];

        $newImages = array_filter($currentImages, function ($path) use ($imagePathToRemove) {
            return $path !== $imagePathToRemove;
        });
        $newImages = array_values($newImages); // Reset index

        $model->update(['images' => empty($newImages) ? null : $newImages]);
        FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }

    private function buildUngDungDaDangPayload(?array $existing, array $input): array
    {
        $existing = is_array($existing) ? $existing : [];
        $payload = [];

        foreach (self::APPLICATION_SLOTS as $slot) {
            $current = is_array($existing[$slot] ?? null) ? $existing[$slot] : [];
            $slotInput = is_array($input[$slot] ?? null) ? $input[$slot] : [];
            $oldImage = $current['image'] ?? null;
            $image = $oldImage;

            if (($slotInput['image'] ?? null) instanceof UploadedFile) {
                $image = FileUploadHelper::replace($slotInput['image'], $oldImage, 'gach_trang_tri/ung_dung');
            }

            $title = array_key_exists('title', $slotInput)
                ? trim((string) $slotInput['title'])
                : ($current['title'] ?? null);

            $payload[$slot] = [
                'title' => $title !== '' ? $title : null,
                'image' => $image,
            ];
        }

        return $payload;
    }
}
