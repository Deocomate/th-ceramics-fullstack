<?php

namespace App\Services;

use App\Models\DinhMucNgoiHaiCo;
use Illuminate\Validation\ValidationException;

class DinhMucNgoiHaiCoService
{
    public function getAll()
    {
        return DinhMucNgoiHaiCo::query()->orderBy('roof_type', 'asc')->get();
    }

    public function create(array $data)
    {
        if (DinhMucNgoiHaiCo::query()->where('roof_type', $data['roof_type'])->exists()) {
            throw ValidationException::withMessages(['roof_type' => 'Loại mái này đã tồn tại định mức.']);
        }

        return DinhMucNgoiHaiCo::query()->create($data);
    }

    public function update(int $id, array $data)
    {
        /** @var DinhMucNgoiHaiCo $model */
        $model = DinhMucNgoiHaiCo::query()->findOrFail($id);

        if (DinhMucNgoiHaiCo::query()->where('roof_type', $data['roof_type'])->where('dinh_muc_ngoi_hai_co_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['roof_type' => 'Loại mái này đã tồn tại.']);
        }

        $model->fill($data)->save();

        return $model->fresh();
    }

    public function destroy(int $id)
    {
        DinhMucNgoiHaiCo::destroy($id);
    }
}
