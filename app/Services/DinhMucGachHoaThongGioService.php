<?php
namespace App\Services;

use App\Models\DinhMucGachHoaThongGio;
use Illuminate\Validation\ValidationException;

class DinhMucGachHoaThongGioService
{
    public function getAll()
    {
        return DinhMucGachHoaThongGio::query()->orderBy('brick_type', 'asc')->get();
    }

    public function create(array $data)
    {
        if (DinhMucGachHoaThongGio::query()->where('brick_type', $data['brick_type'])->exists()) {
            throw ValidationException::withMessages(['brick_type' => 'Loại gạch này đã tồn tại định mức.']);
        }
        return DinhMucGachHoaThongGio::query()->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = DinhMucGachHoaThongGio::query()->findOrFail($id);

        if (DinhMucGachHoaThongGio::query()->where('brick_type', $data['brick_type'])->where('dinh_muc_gach_hoa_thong_gio_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['brick_type' => 'Loại gạch này đã tồn tại.']);
        }

        $model->fill($data)->save();
        return $model->fresh();
    }

    public function destroy(int $id)
    {
        DinhMucGachHoaThongGio::destroy($id);
    }
}