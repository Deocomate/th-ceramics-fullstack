<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\GiaiThuongThanhTuu;
use Illuminate\Http\UploadedFile;

class GiaiThuongThanhTuuService
{
    public function getAll()
    {
        return GiaiThuongThanhTuu::query()->latest()->get();
    }

    public function findById(int $id): GiaiThuongThanhTuu
    {
        return GiaiThuongThanhTuu::query()->findOrFail($id);
    }

    public function store(array $data): GiaiThuongThanhTuu
    {
        $imagePath = FileUploadHelper::upload($data['image'], 'giai_thuong_thanh_tuu/images');
        
        /** @var GiaiThuongThanhTuu $model */
        $model = GiaiThuongThanhTuu::query()->create([
            'image' => $imagePath,
            'des'   => $data['des'],
        ]);

        return $model;
    }

    public function update(int $id, array $data): GiaiThuongThanhTuu
    {
        $model = $this->findById($id);
        $fillable =[
            'des' => $data['des'] ?? $model->des,
        ];
        
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $fillable['image'] = FileUploadHelper::replace($data['image'], $model->image, 'giai_thuong_thanh_tuu/images');
        }

        $model->fill($fillable)->save();
        
        return $model->fresh();
    }

    public function destroy(int $id): void
    {
        $model = $this->findById($id);
        FileUploadHelper::delete($model->image);
        
        GiaiThuongThanhTuu::destroy($id);
    }
}