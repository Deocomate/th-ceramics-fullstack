<?php

namespace App\Domains\Content\Infrastructure\Services;

use App\Domains\Content\Infrastructure\Models\ContactPageConfig;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ContactPageConfigService
{
    public function getFirstRecord(): ContactPageConfig
    {
        return ContactPageConfig::query()->firstOrFail();
    }

    /** @param array<string, mixed> $data */
    public function update(array $data): ContactPageConfig
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $imageFields = ['map_image'];

            foreach ($imageFields as $field) {
                if (isset($data[$field]) && $data[$field] instanceof UploadedFile) {
                    $data[$field] = FileUploadHelper::replace($data[$field], $model->{$field}, 'pages/contact');
                } else {
                    unset($data[$field]);
                }
            }

            $fillable = array_intersect_key($data, array_flip($model->getFillable()));
            $model->update($fillable);

            return $model->fresh();
        });
    }
}
