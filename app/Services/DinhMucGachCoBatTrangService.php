<?php

namespace App\Services;

use App\Models\DinhMucGachCoBatTrang;
use Illuminate\Validation\ValidationException;

class DinhMucGachCoBatTrangService
{
    public function getAll()
    {
        return DinhMucGachCoBatTrang::query()->orderBy('brick_type', 'asc')->get();
    }

    public function create(array $data)
    {
        if (DinhMucGachCoBatTrang::query()->where('brick_type', $data['brick_type'])->exists()) {
            throw ValidationException::withMessages(['brick_type' => 'Loại gạch này đã tồn tại định mức.']);
        }

        return DinhMucGachCoBatTrang::query()->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = DinhMucGachCoBatTrang::query()->findOrFail($id);

        if (DinhMucGachCoBatTrang::query()->where('brick_type', $data['brick_type'])->where('dinh_muc_gach_co_bat_trang_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['brick_type' => 'Loại gạch này đã tồn tại.']);
        }

        $model->fill($data)->save();

        return $model->fresh();
    }

    public function destroy(int $id)
    {
        DinhMucGachCoBatTrang::destroy($id);
    }
}
