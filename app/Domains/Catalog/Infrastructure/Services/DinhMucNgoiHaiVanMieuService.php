<?php

namespace App\Domains\Catalog\Infrastructure\Services;

use App\Domains\Catalog\Infrastructure\Models\DinhMucNgoiHaiVanMieu;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class DinhMucNgoiHaiVanMieuService
{
    /** @return Collection<int, DinhMucNgoiHaiVanMieu> */
    public function getAll(): Collection
    {
        return DinhMucNgoiHaiVanMieu::query()->orderBy('roof_type', 'asc')->get();
    }

    public function create(array $data): DinhMucNgoiHaiVanMieu
    {
        if (DinhMucNgoiHaiVanMieu::query()->where('roof_type', $data['roof_type'])->exists()) {
            throw ValidationException::withMessages(['roof_type' => 'Loại mái này đã tồn tại định mức.']);
        }

        return DinhMucNgoiHaiVanMieu::query()->create($data);
    }

    public function update(int $id, array $data): DinhMucNgoiHaiVanMieu
    {
        /** @var DinhMucNgoiHaiVanMieu $model */
        $model = DinhMucNgoiHaiVanMieu::query()->findOrFail($id);

        if (DinhMucNgoiHaiVanMieu::query()->where('roof_type', $data['roof_type'])->where('dinh_muc_ngoi_hai_van_mieu_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['roof_type' => 'Loại mái này đã tồn tại.']);
        }

        $model->fill($data)->save();

        return $model->fresh();
    }

    public function destroy(int $id): void
    {
        DinhMucNgoiHaiVanMieu::destroy($id);
    }
}
