<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\MauSacNgoiAmDuongCt;
use Illuminate\Http\UploadedFile;

class MauSacNgoiAmDuongCtService
{
    public function getAll()
    {
        return MauSacNgoiAmDuongCt::query()->latest()->get();
    }

    public function create(array $data)
    {
        $fillable = [
            'name' => $data['name'],
        ];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $fillable['image'] = FileUploadHelper::upload($data['image'], 'mau_sac_ngoi_am_duong_ct/images');
        }

        return MauSacNgoiAmDuongCt::query()->create($fillable);
    }

    public function update(int $id, array $data)
    {
        /** @var MauSacNgoiAmDuongCt $model */
        $model = MauSacNgoiAmDuongCt::query()->findOrFail($id);

        $fillable = [
            'name' => $data['name'],
        ];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $fillable['image'] = FileUploadHelper::replace($data['image'], $model->image, 'mau_sac_ngoi_am_duong_ct/images');
        }

        $model->fill($fillable)->save();

        return $model->fresh();
    }

    public function destroy(int $id)
    {
        $model = MauSacNgoiAmDuongCt::query()->findOrFail($id);
        FileUploadHelper::delete($model->image);

        MauSacNgoiAmDuongCt::destroy($id);
    }
}
