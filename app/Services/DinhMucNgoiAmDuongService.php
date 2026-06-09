<?php

namespace App\Services;

use App\Models\DinhMucNgoiAmDuong;
use Illuminate\Validation\ValidationException;

class DinhMucNgoiAmDuongService
{
    public function getAll()
    {
        return DinhMucNgoiAmDuong::query()
            ->orderBy('roof_type', 'asc')
            ->orderBy('tile_type', 'asc')
            ->get();
    }

    public function create(array $data)
    {
        if (DinhMucNgoiAmDuong::query()->where('roof_type', $data['roof_type'])
            ->where('tile_type', $data['tile_type'])->exists()) {
            throw ValidationException::withMessages([
                'roof_type' => 'Định mức cho Loại mái và Loại ngói này đã tồn tại trên hệ thống.',
            ]);
        }

        return DinhMucNgoiAmDuong::query()->create($data);
    }

    public function update(int $id, array $data)
    {
        /** @var DinhMucNgoiAmDuong $model */
        $model = DinhMucNgoiAmDuong::query()->findOrFail($id);

        if (DinhMucNgoiAmDuong::query()->where('roof_type', $data['roof_type'])
            ->where('tile_type', $data['tile_type'])
            ->where('dinh_muc_ngoi_am_duong_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages([
                'roof_type' => 'Định mức cho Loại mái và Loại ngói này đã tồn tại.',
            ]);
        }

        $model->fill($data)->save();

        return $model->fresh();
    }

    public function destroy(int $id)
    {
        DinhMucNgoiAmDuong::destroy($id);
    }
}
