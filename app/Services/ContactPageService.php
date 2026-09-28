<?php

namespace App\Services;

use App\Domains\Media\FileUploadHelper;
use App\Models\PageContact;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ContactPageService
{
    public function getFirstRecord(): PageContact
    {
        return PageContact::query()->firstOrFail();
    }

    public function update(array $data): PageContact
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
