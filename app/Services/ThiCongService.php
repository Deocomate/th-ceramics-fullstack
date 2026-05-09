<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\ThiCong;
use Illuminate\Http\UploadedFile;

class ThiCongService
{
    public function getAll(int $perPage = 10)
    {
        return ThiCong::query()->latest()->paginate($perPage);
    }

    public function findById(int $id): ThiCong
    {
        return ThiCong::query()->findOrFail($id);
    }

    public function store(array $data): ThiCong
    {
        $imagePath = FileUploadHelper::upload($data['anh'], 'thi_cong/images');
        
        return ThiCong::query()->create([
            'tieu_de'      => $data['tieu_de'],
            'link_youtube' => $data['link_youtube'] ?? null,
            'anh'          => $imagePath,
        ]);
    }

    public function update(int $id, array $data): ThiCong
    {
        $model = $this->findById($id);
        
        $fillable =[
            'tieu_de'      => $data['tieu_de'] ?? $model->tieu_de,
            'link_youtube' => array_key_exists('link_youtube', $data) ? $data['link_youtube'] : $model->link_youtube,
        ];
        
        if (isset($data['anh']) && $data['anh'] instanceof UploadedFile) {
            $fillable['anh'] = FileUploadHelper::replace($data['anh'], $model->anh, 'thi_cong/images');
        }

        $model->fill($fillable)->save();
        
        return $model->fresh();
    }

    public function destroy(int $id): void
    {
        $model = $this->findById($id);
        FileUploadHelper::delete($model->anh);
        
        $model->delete();
    }
}