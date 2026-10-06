<?php

namespace App\Domains\Catalog\Infrastructure\Services;

use App\Domains\Catalog\Infrastructure\Models\UsageNormBatTrangAntiqueBrick;
use Illuminate\Validation\ValidationException;

class UsageNormBatTrangAntiqueBrickService
{
    public function getAll()
    {
        return UsageNormBatTrangAntiqueBrick::query()->orderBy('brick_type', 'asc')->get();
    }

    public function create(array $data)
    {
        if (UsageNormBatTrangAntiqueBrick::query()->where('brick_type', $data['brick_type'])->exists()) {
            throw ValidationException::withMessages(['brick_type' => 'Loại gạch này đã tồn tại định mức.']);
        }

        return UsageNormBatTrangAntiqueBrick::query()->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = UsageNormBatTrangAntiqueBrick::query()->findOrFail($id);

        if (UsageNormBatTrangAntiqueBrick::query()->where('brick_type', $data['brick_type'])->where('dinh_muc_gach_co_bat_trang_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['brick_type' => 'Loại gạch này đã tồn tại.']);
        }

        $model->fill($data)->save();

        return $model->fresh();
    }

    public function destroy(int $id)
    {
        UsageNormBatTrangAntiqueBrick::destroy($id);
    }
}
