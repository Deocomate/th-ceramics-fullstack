<?php

namespace App\Services;

use App\Models\DanhMucDuAn;
use Illuminate\Validation\ValidationException;

class DanhMucDuAnService
{
    public function getAll(string $status = 'active')
    {
        $query = DanhMucDuAn::query()->withCount('duAns')->latest();

        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }

        return $query->get();
    }

    public function create(array $data)
    {
        if (DanhMucDuAn::query()->where('ten_danh_muc', $data['ten_danh_muc'])->exists()) {
            throw ValidationException::withMessages(['ten_danh_muc' => 'Tên danh mục này đã tồn tại.']);
        }

        return DanhMucDuAn::query()->create([
            'ten_danh_muc' => $data['ten_danh_muc'],
            'is_delete' => 0,
        ]);
    }

    public function update(int $id, array $data)
    {
        $model = DanhMucDuAn::query()->findOrFail($id);

        if (DanhMucDuAn::query()->where('ten_danh_muc', $data['ten_danh_muc'])->where('danh_muc_du_an_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['ten_danh_muc' => 'Tên danh mục này đã tồn tại.']);
        }

        $model->update(['ten_danh_muc' => $data['ten_danh_muc']]);
        return $model->fresh();
    }

    public function toggleStatus(int $id, int $status): void
    {
        $model = DanhMucDuAn::query()->findOrFail($id);
        $model->update(['is_delete' => $status]);
    }
}