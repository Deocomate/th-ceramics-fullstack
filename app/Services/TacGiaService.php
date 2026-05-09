<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\TacGia;
use Illuminate\Http\UploadedFile;

class TacGiaService
{
    public function getAll(int $perPage = 10)
    {
        return TacGia::query()->latest()->paginate($perPage); // Đổi ->get() thành ->paginate()
    }

    public function findById(int $id): TacGia
    {
        return TacGia::query()->findOrFail($id);
    }

    public function store(array $data): TacGia
    {
        $imagePath = FileUploadHelper::upload($data['anh_dai_dien'], 'tac_gia/images');
        
        return TacGia::query()->create([
            'ten_tac_gia'   => $data['ten_tac_gia'],
            'link_fb'       => $data['link_fb'] ?? null,
            'link_linkedin' => $data['link_linkedin'] ?? null,
            'link_tele'     => $data['link_tele'] ?? null,
            'link_sky'      => $data['link_sky'] ?? null,
            'mo_ta'         => $data['mo_ta'],
            'anh_dai_dien'  => $imagePath,
        ]);
    }

    public function update(int $id, array $data): TacGia
    {
        $model = $this->findById($id);
        
        $fillable =[
            'ten_tac_gia'   => $data['ten_tac_gia'] ?? $model->ten_tac_gia,
            'link_fb'       => $data['link_fb'] ?? $model->link_fb,
            'link_linkedin' => $data['link_linkedin'] ?? $model->link_linkedin,
            'link_tele'     => $data['link_tele'] ?? $model->link_tele,
            'link_sky'      => $data['link_sky'] ?? $model->link_sky,
            'mo_ta'         => $data['mo_ta'] ?? $model->mo_ta,
        ];
        
        if (isset($data['anh_dai_dien']) && $data['anh_dai_dien'] instanceof UploadedFile) {
            $fillable['anh_dai_dien'] = FileUploadHelper::replace($data['anh_dai_dien'], $model->anh_dai_dien, 'tac_gia/images');
        }

        $model->fill($fillable)->save();
        
        return $model->fresh();
    }

    public function destroy(int $id): void
    {
        $model = $this->findById($id);
        FileUploadHelper::delete($model->anh_dai_dien);
        
        $model->delete();
    }
}