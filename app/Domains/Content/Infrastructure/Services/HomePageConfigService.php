<?php

namespace App\Domains\Content\Infrastructure\Services;

use App\Domains\Content\Domain\HomePageNumbers;
use App\Domains\Content\Infrastructure\Models\HomePageConfig;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class HomePageConfigService
{
    private const SITE_FLAGS = ['is_ecommerce_enabled', 'is_content_protection_enabled', 'is_devtools_guard_enabled'];

    public function getFirstRecord(): HomePageConfig
    {
        $record = HomePageConfig::query()->first();
        if (! $record) {
            $record = HomePageConfig::query()->create([
                'banner' => [],
                'khach_hang_doi_tac' => [],
                'loi_tri_an' => [],
                'loi_tri_an_anh' => '',
                've_chung_toi_logo' => [],
                'video' => null,
                'nhung_con_so' => [],
                'showroom_images' => [],
                'showroom_noidung' => null,
                'is_ecommerce_enabled' => true,
                'is_content_protection_enabled' => false,
                'is_devtools_guard_enabled' => false,
            ]);
        }

        return $record;
    }

    /**
     * Site-wide switches share the home page record but are saved on their own,
     * so the home page content is left as it is.
     *
     * @param  array<string, mixed>  $flags
     */
    public function updateSiteSettings(array $flags): HomePageConfig
    {
        $model = $this->getFirstRecord();
        $model->update(array_map('boolval', array_intersect_key($flags, array_flip(self::SITE_FLAGS))));

        return $model->fresh();
    }

    /** @param array<string, mixed> $data */
    public function update(array $data): HomePageConfig
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $fillable = [];

            if (isset($data['loi_tri_an_anh']) && $data['loi_tri_an_anh'] instanceof UploadedFile) {
                $fillable['loi_tri_an_anh'] = FileUploadHelper::replace($data['loi_tri_an_anh'], $model->loi_tri_an_anh, 'trang_chu/images');
            }

            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }
            if (array_key_exists('showroom_noidung', $data)) {
                $fillable['showroom_noidung'] = $data['showroom_noidung'];
            }
            foreach (self::SITE_FLAGS as $flag) {
                if (array_key_exists($flag, $data)) {
                    $fillable[$flag] = (bool) $data[$flag];
                }
            }

            if (isset($data['loi_tri_an']) && is_array($data['loi_tri_an'])) {
                $fillable['loi_tri_an'] = array_values(array_filter(array_map('trim', $data['loi_tri_an'])));
            } else {
                $fillable['loi_tri_an'] = [];
            }

            if (isset($data['nhung_con_so']) && is_array($data['nhung_con_so'])) {
                $fillable['nhung_con_so'] = HomePageNumbers::sanitize($data['nhung_con_so']);
            } else {
                $fillable['nhung_con_so'] = [];
            }

            $galleries = [
                'banner' => 'new_banner',
                'khach_hang_doi_tac' => 'new_khach_hang',
                've_chung_toi_logo' => 'new_ve_chung_toi_logo',
                'showroom_images' => 'new_showroom_images',
            ];

            foreach ($galleries as $dbField => $newField) {
                $existing = is_array($model->{$dbField}) ? $model->{$dbField} : [];
                $deleteField = str_replace('new_', 'delete_', $newField);

                if (! empty($data[$deleteField]) && is_array($data[$deleteField])) {
                    foreach ($data[$deleteField] as $idx) {
                        $idx = (int) $idx;
                        if (isset($existing[$idx])) {
                            FileUploadHelper::delete($existing[$idx]);
                            unset($existing[$idx]);
                        }
                    }
                    $existing = array_values($existing);
                }

                if (! empty($data[$newField]) && is_array($data[$newField])) {
                    foreach ($data[$newField] as $file) {
                        if ($file instanceof UploadedFile) {
                            $existing[] = FileUploadHelper::upload($file, "trang_chu/{$dbField}");
                        }
                    }
                }
                $fillable[$dbField] = $existing;
            }

            $model->update($fillable);

            return $model->fresh();
        });
    }
}
