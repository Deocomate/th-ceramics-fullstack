<?php

namespace App\Services;

use App\Models\DinhMucGachTrangTri;
use Illuminate\Validation\ValidationException;

class DinhMucGachTrangTriService
{
    public function getAll()
    {
        return DinhMucGachTrangTri::query()->orderBy('brick_type', 'asc')->get();
    }

    public function create(array $data)
    {
        if (DinhMucGachTrangTri::query()->where('brick_type', $data['brick_type'])->exists()) {
            throw ValidationException::withMessages(['brick_type' => 'Loại gạch này đã tồn tại định mức.']);
        }

        return DinhMucGachTrangTri::query()->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = DinhMucGachTrangTri::query()->findOrFail($id);

        if (DinhMucGachTrangTri::query()->where('brick_type', $data['brick_type'])->where('dinh_muc_gach_trang_tri_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['brick_type' => 'Loại gạch này đã tồn tại.']);
        }

        $model->fill($data)->save();

        return $model->fresh();
    }

    public function destroy(int $id)
    {
        DinhMucGachTrangTri::destroy($id);
    }
}
