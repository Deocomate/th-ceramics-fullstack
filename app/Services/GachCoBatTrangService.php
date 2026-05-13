<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\GachCoBatTrang;
use App\Models\GachCoBatTrangAnh;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class GachCoBatTrangService
{
    public const SECTION_KEYS = ['section_bat', 'section_that', 'section_the'];

    private const DEFAULT_COLORS = ['#A98467', '#B22222', '#5D5FEF'];

    public function getFirstRecord(): GachCoBatTrang
    {
        return GachCoBatTrang::with('anh')->firstOrFail();
    }

    public function update(array $data): GachCoBatTrang
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $fillable = [];

            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $model->thumbnail_main, 'gach_co_bat_trang/images');
            }

            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            $currentImages = is_array($model->images) ? $model->images : [];
            $imagesChanged = false;

            if (! empty($data['cong_doan_order']) && is_array($data['cong_doan_order'])) {
                $orderedImages = array_values(array_filter(
                    $data['cong_doan_order'],
                    fn ($path) => is_string($path) && in_array($path, $currentImages, true)
                ));
                $missingImages = array_values(array_diff($currentImages, $orderedImages));
                $currentImages = array_merge($orderedImages, $missingImages);
                $imagesChanged = true;
            }

            if (! empty($data['cong_doan_images']) && is_array($data['cong_doan_images'])) {
                foreach ($data['cong_doan_images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $currentImages[] = FileUploadHelper::upload($file, 'gach_co_bat_trang/cong_doan_che_tac');
                        $imagesChanged = true;
                    }
                }
            }

            if ($imagesChanged) {
                $fillable['images'] = $currentImages;
            }

            foreach (self::SECTION_KEYS as $sectionKey) {
                if (
                    array_key_exists($sectionKey, $data)
                    || array_key_exists($sectionKey.'_new_images', $data)
                    || array_key_exists($sectionKey.'_gallery_order', $data)
                ) {
                    $fillable[$sectionKey] = $this->buildSectionPayload(
                        $model->{$sectionKey},
                        is_array($data[$sectionKey] ?? null) ? $data[$sectionKey] : [],
                        is_array($data[$sectionKey.'_new_images'] ?? null) ? $data[$sectionKey.'_new_images'] : [],
                        is_array($data[$sectionKey.'_gallery_order'] ?? null) ? $data[$sectionKey.'_gallery_order'] : []
                    );
                }
            }

            if (! empty($fillable)) {
                $model->update($fillable);
            }

            return $model->fresh();
        });
    }

    public function deleteAnh(int $anhId): void
    {
        $anh = GachCoBatTrangAnh::findOrFail($anhId);
        FileUploadHelper::delete($anh->image);
        $anh->delete();
    }

    public function removeImageFromJson(string $imagePathToRemove): GachCoBatTrang
    {
        $model = $this->getFirstRecord();
        $currentImages = is_array($model->images) ? $model->images : [];

        $newImages = array_values(array_filter(
            $currentImages,
            fn ($path) => $path !== $imagePathToRemove
        ));

        $model->update(['images' => empty($newImages) ? null : $newImages]);
        FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }

    public function removeSectionImage(string $sectionKey, string $imagePathToRemove): GachCoBatTrang
    {
        if (! in_array($sectionKey, self::SECTION_KEYS, true)) {
            throw new InvalidArgumentException('Phân khu không hợp lệ.');
        }

        $model = $this->getFirstRecord();
        $section = $this->normalizeSection($model->{$sectionKey});
        $section['gallery'] = array_values(array_filter(
            $section['gallery'],
            fn ($path) => $path !== $imagePathToRemove
        ));

        $model->update([$sectionKey => $section]);
        FileUploadHelper::delete($imagePathToRemove);

        return $model->fresh();
    }

    private function buildSectionPayload(?array $existing, array $input, array $newImages, array $galleryOrder): array
    {
        $section = $this->normalizeSection($existing);

        foreach (['title', 'subtitle', 'description'] as $field) {
            if (array_key_exists($field, $input)) {
                $value = trim((string) $input[$field]);
                $section[$field] = $value !== '' ? $value : null;
            }
        }

        if (array_key_exists('colors', $input) && is_array($input['colors'])) {
            $colors = array_values(array_filter(
                array_map(fn ($color) => trim((string) $color), $input['colors']),
                fn ($color) => preg_match('/^#[0-9A-Fa-f]{6}$/', $color) === 1
            ));

            $section['colors'] = array_slice(array_pad($colors, 3, null), 0, 3);
        }

        if (! empty($galleryOrder)) {
            $orderedGallery = array_values(array_filter(
                $galleryOrder,
                fn ($path) => is_string($path) && in_array($path, $section['gallery'], true)
            ));
            $missingGallery = array_values(array_diff($section['gallery'], $orderedGallery));
            $section['gallery'] = array_merge($orderedGallery, $missingGallery);
        }

        foreach ($newImages as $file) {
            if ($file instanceof UploadedFile) {
                $section['gallery'][] = FileUploadHelper::upload($file, 'gach_co_bat_trang/section_gallery');
            }
        }

        return $section;
    }

    private function normalizeSection(?array $section): array
    {
        $section = is_array($section) ? $section : [];
        $colors = is_array($section['colors'] ?? null) ? array_values($section['colors']) : self::DEFAULT_COLORS;

        return [
            'title' => $section['title'] ?? null,
            'subtitle' => $section['subtitle'] ?? null,
            'description' => $section['description'] ?? null,
            'colors' => array_slice(array_pad($colors, 3, null), 0, 3),
            'gallery' => is_array($section['gallery'] ?? null) ? array_values($section['gallery']) : [],
        ];
    }
}
