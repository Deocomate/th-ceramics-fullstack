<?php

namespace App\Domains\Catalog\Infrastructure\Services;

use App\Domains\Catalog\Infrastructure\Models\UsageNormAncientFishScaleRoofTile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class UsageNormAncientFishScaleRoofTileService
{
    /** @return Collection<int, UsageNormAncientFishScaleRoofTile> */
    public function getAll(): Collection
    {
        return UsageNormAncientFishScaleRoofTile::query()->orderBy('roof_type', 'asc')->get();
    }

    public function create(array $data): UsageNormAncientFishScaleRoofTile
    {
        if (UsageNormAncientFishScaleRoofTile::query()->where('roof_type', $data['roof_type'])->exists()) {
            throw ValidationException::withMessages(['roof_type' => 'Loại mái này đã tồn tại định mức.']);
        }

        return UsageNormAncientFishScaleRoofTile::query()->create($data);
    }

    public function update(int $id, array $data): UsageNormAncientFishScaleRoofTile
    {
        /** @var UsageNormAncientFishScaleRoofTile $model */
        $model = UsageNormAncientFishScaleRoofTile::query()->findOrFail($id);

        if (UsageNormAncientFishScaleRoofTile::query()->where('roof_type', $data['roof_type'])->where('dinh_muc_ngoi_hai_co_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['roof_type' => 'Loại mái này đã tồn tại.']);
        }

        $model->fill($data)->save();

        return $model->fresh();
    }

    public function destroy(int $id): void
    {
        UsageNormAncientFishScaleRoofTile::destroy($id);
    }
}
