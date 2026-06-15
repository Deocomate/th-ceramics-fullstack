<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\TrangDuAn;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class TrangDuAnService
{
    public function getFirstRecord(): TrangDuAn
    {
        return TrangDuAn::query()->firstOrCreate(
            ['trang_du_an_id' => 1],
            [
                'promo_title' => "Gạch thông\ngió 300x300\nthường",
                'promo_image' => 'assets/images/news-detail-5.png',
                'promo_cta_label' => 'XEM CATALOG',
                'promo_cta_url' => '/san-pham/gach-hoa-thong-gio',
                'promo_enabled' => true,
            ]
        );
    }

    public function update(array $data): TrangDuAn
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $fillable = [];

            if (array_key_exists('promo_title', $data)) {
                $fillable['promo_title'] = $data['promo_title'];
            }

            if (isset($data['promo_image']) && $data['promo_image'] instanceof UploadedFile) {
                $fillable['promo_image'] = FileUploadHelper::replace(
                    $data['promo_image'],
                    $model->promo_image,
                    'trang_du_an/promo'
                );
            }

            if (array_key_exists('promo_cta_label', $data)) {
                $fillable['promo_cta_label'] = $data['promo_cta_label'];
            }

            if (array_key_exists('promo_cta_url', $data)) {
                $fillable['promo_cta_url'] = $data['promo_cta_url'];
            }

            if (array_key_exists('promo_enabled', $data)) {
                $fillable['promo_enabled'] = (bool) $data['promo_enabled'];
            }

            if (! empty($fillable)) {
                $model->update($fillable);
            }

            return $model->fresh();
        });
    }
}
