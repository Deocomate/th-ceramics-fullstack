<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\LanCanGomXu;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class LanCanGomXuService
{
    public function getFirstRecord(): LanCanGomXu
    {
        return LanCanGomXu::query()->firstOrFail();
    }

    public function update(array $data): LanCanGomXu
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $fillable = [];

            if (isset($data['thumbnail_main']) && $data['thumbnail_main'] instanceof UploadedFile) {
                $fillable['thumbnail_main'] = FileUploadHelper::replace($data['thumbnail_main'], $model->thumbnail_main, 'lan_can_gom_xu/images');
            }

            if (array_key_exists('video', $data)) {
                $fillable['video'] = $data['video'];
            }

            foreach (['section_1_image', 'section_2_image'] as $field) {
                if (isset($data[$field]) && $data[$field] instanceof UploadedFile) {
                    $fillable[$field] = FileUploadHelper::replace($data[$field], $model->{$field}, 'lan_can_gom_xu/sections');
                }
            }

            foreach (['section_1_title', 'section_2_title', 'section_1_products', 'section_2_products'] as $field) {
                if (array_key_exists($field, $data)) {
                    $fillable[$field] = $data[$field];
                }
            }

            if (! empty($fillable)) {
                $model->fill($fillable)->save();
            }

            return $model->fresh();
        });
    }
}
