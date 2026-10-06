<?php

namespace App\Domains\Catalog\Infrastructure\Services;

use App\Domains\Catalog\Infrastructure\Models\UsageNormDecorativeTile;
use Illuminate\Validation\ValidationException;

class UsageNormDecorativeTileService
{
    public function getAll()
    {
        return UsageNormDecorativeTile::query()->orderBy('brick_type', 'asc')->get();
    }

    public function create(array $data)
    {
        if (UsageNormDecorativeTile::query()->where('brick_type', $data['brick_type'])->exists()) {
            throw ValidationException::withMessages(['brick_type' => 'Loại gạch này đã tồn tại định mức.']);
        }

        return UsageNormDecorativeTile::query()->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = UsageNormDecorativeTile::query()->findOrFail($id);

        if (UsageNormDecorativeTile::query()->where('brick_type', $data['brick_type'])->where('dinh_muc_gach_trang_tri_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['brick_type' => 'Loại gạch này đã tồn tại.']);
        }

        $model->fill($data)->save();

        return $model->fresh();
    }

    public function destroy(int $id)
    {
        UsageNormDecorativeTile::destroy($id);
    }
}
