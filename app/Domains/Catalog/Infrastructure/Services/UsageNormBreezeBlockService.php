<?php

namespace App\Domains\Catalog\Infrastructure\Services;

use App\Domains\Catalog\Infrastructure\Models\UsageNormBreezeBlock;
use Illuminate\Validation\ValidationException;

class UsageNormBreezeBlockService
{
    public function getAll()
    {
        return UsageNormBreezeBlock::query()->orderBy('brick_type', 'asc')->get();
    }

    public function create(array $data)
    {
        if (UsageNormBreezeBlock::query()->where('brick_type', $data['brick_type'])->exists()) {
            throw ValidationException::withMessages(['brick_type' => 'Loại gạch này đã tồn tại định mức.']);
        }

        return UsageNormBreezeBlock::query()->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = UsageNormBreezeBlock::query()->findOrFail($id);

        if (UsageNormBreezeBlock::query()->where('brick_type', $data['brick_type'])->where('dinh_muc_gach_hoa_thong_gio_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['brick_type' => 'Loại gạch này đã tồn tại.']);
        }

        $model->fill($data)->save();

        return $model->fresh();
    }

    public function destroy(int $id)
    {
        UsageNormBreezeBlock::destroy($id);
    }
}
