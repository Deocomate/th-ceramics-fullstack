<?php

namespace App\Domains\Catalog\Infrastructure\Services;

use App\Domains\Catalog\Infrastructure\Models\UsageNormVanMieuFishScaleRoofTile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class UsageNormVanMieuFishScaleRoofTileService
{
    /** @return Collection<int, UsageNormVanMieuFishScaleRoofTile> */
    public function getAll(): Collection
    {
        return UsageNormVanMieuFishScaleRoofTile::query()->orderBy('roof_type', 'asc')->get();
    }

    public function create(array $data): UsageNormVanMieuFishScaleRoofTile
    {
        if (UsageNormVanMieuFishScaleRoofTile::query()->where('roof_type', $data['roof_type'])->exists()) {
            throw ValidationException::withMessages(['roof_type' => 'Loại mái này đã tồn tại định mức.']);
        }

        return UsageNormVanMieuFishScaleRoofTile::query()->create($data);
    }

    public function update(int $id, array $data): UsageNormVanMieuFishScaleRoofTile
    {
        /** @var UsageNormVanMieuFishScaleRoofTile $model */
        $model = UsageNormVanMieuFishScaleRoofTile::query()->findOrFail($id);

        if (UsageNormVanMieuFishScaleRoofTile::query()->where('roof_type', $data['roof_type'])->where('dinh_muc_ngoi_hai_van_mieu_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['roof_type' => 'Loại mái này đã tồn tại.']);
        }

        $model->fill($data)->save();

        return $model->fresh();
    }

    public function destroy(int $id): void
    {
        UsageNormVanMieuFishScaleRoofTile::destroy($id);
    }
}
