<?php

namespace App\Domains\Catalog\Infrastructure\Services;

use App\Domains\Catalog\Infrastructure\Models\UsageNormYinYangRoofTile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class UsageNormYinYangRoofTileService
{
    /** @return Collection<int, UsageNormYinYangRoofTile> */
    public function getAll(): Collection
    {
        return UsageNormYinYangRoofTile::query()
            ->orderBy('roof_type', 'asc')
            ->orderBy('tile_type', 'asc')
            ->get();
    }

    public function create(array $data): UsageNormYinYangRoofTile
    {
        if (UsageNormYinYangRoofTile::query()->where('roof_type', $data['roof_type'])
            ->where('tile_type', $data['tile_type'])->exists()) {
            throw ValidationException::withMessages([
                'roof_type' => 'Định mức cho Loại mái và Loại ngói này đã tồn tại trên hệ thống.',
            ]);
        }

        return UsageNormYinYangRoofTile::query()->create($data);
    }

    public function update(int $id, array $data): UsageNormYinYangRoofTile
    {
        /** @var UsageNormYinYangRoofTile $model */
        $model = UsageNormYinYangRoofTile::query()->findOrFail($id);

        if (UsageNormYinYangRoofTile::query()->where('roof_type', $data['roof_type'])
            ->where('tile_type', $data['tile_type'])
            ->where('dinh_muc_ngoi_am_duong_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages([
                'roof_type' => 'Định mức cho Loại mái và Loại ngói này đã tồn tại.',
            ]);
        }

        $model->fill($data)->save();

        return $model->fresh();
    }

    public function destroy(int $id): void
    {
        UsageNormYinYangRoofTile::destroy($id);
    }
}
