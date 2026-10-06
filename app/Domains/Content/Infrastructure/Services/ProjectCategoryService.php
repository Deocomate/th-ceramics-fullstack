<?php

namespace App\Domains\Content\Infrastructure\Services;

use App\Domains\Content\Infrastructure\Models\ProjectCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class ProjectCategoryService
{
    /** @return Collection<int, ProjectCategory> */
    public function getAll(string $status = 'active'): Collection
    {
        $query = ProjectCategory::query()->withCount('projects')->latest();

        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }

        return $query->get();
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): ProjectCategory
    {
        if (ProjectCategory::query()->where('ten_danh_muc', $data['ten_danh_muc'])->exists()) {
            throw ValidationException::withMessages(['ten_danh_muc' => 'Tên danh mục này đã tồn tại.']);
        }

        return ProjectCategory::query()->create([
            'ten_danh_muc' => $data['ten_danh_muc'],
            'is_delete' => 0,
        ]);
    }

    /** @param array<string, mixed> $data */
    public function update(int $id, array $data): ProjectCategory
    {
        $model = ProjectCategory::query()->findOrFail($id);

        if (ProjectCategory::query()->where('ten_danh_muc', $data['ten_danh_muc'])->where('danh_muc_du_an_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['ten_danh_muc' => 'Tên danh mục này đã tồn tại.']);
        }

        $model->update(['ten_danh_muc' => $data['ten_danh_muc']]);

        return $model->fresh();
    }

    public function toggleStatus(int $id, int $status): void
    {
        $model = ProjectCategory::query()->findOrFail($id);
        $model->update(['is_delete' => $status]);
    }
}
