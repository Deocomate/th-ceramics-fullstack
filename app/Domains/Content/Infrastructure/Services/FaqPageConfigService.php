<?php

namespace App\Domains\Content\Infrastructure\Services;

use App\Domains\Content\Infrastructure\Models\FaqPageConfig;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class FaqPageConfigService
{
    public function getFirstRecord(): FaqPageConfig
    {
        return FaqPageConfig::query()->firstOrFail();
    }

    /** @param array<string, mixed> $data */
    public function update(array $data): FaqPageConfig
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            if (isset($data['banner_image']) && $data['banner_image'] instanceof UploadedFile) {
                $data['banner_image'] = FileUploadHelper::replace(
                    $data['banner_image'],
                    $model->banner_image,
                    'pages/faq'
                );
            } else {
                unset($data['banner_image']);
            }

            $fillable = array_intersect_key($data, array_flip($model->getFillable()));
            $model->update($fillable);

            return $model->fresh();
        });
    }
}
