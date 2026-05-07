<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\PageFaq;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class FaqPageService
{
    public function getFirstRecord(): PageFaq
    {
        return PageFaq::query()->firstOrFail();
    }

    public function update(array $data): PageFaq
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
