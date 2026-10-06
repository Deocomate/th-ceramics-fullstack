<?php

namespace App\Domains\Content\Infrastructure\Services;

use App\Domains\Content\Infrastructure\Models\NewsCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class NewsCategoryService
{
    /** @return Collection<int, NewsCategory> */
    public function getAll(string $status = 'active'): Collection
    {
        $query = NewsCategory::query()->withCount('articles')->latest();

        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }

        return $query->get();
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): NewsCategory
    {
        if (NewsCategory::query()->where('ten_danh_muc', $data['ten_danh_muc'])->exists()) {
            throw ValidationException::withMessages(['ten_danh_muc' => 'Tên danh mục này đã tồn tại.']);
        }

        return NewsCategory::query()->create([
            'ten_danh_muc' => $data['ten_danh_muc'],
            'is_delete' => 0,
        ]);
    }

    /** @param array<string, mixed> $data */
    public function update(int $id, array $data): NewsCategory
    {
        $model = NewsCategory::query()->findOrFail($id);

        if (NewsCategory::query()->where('ten_danh_muc', $data['ten_danh_muc'])->where('danh_muc_tin_tuc_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['ten_danh_muc' => 'Tên danh mục này đã tồn tại.']);
        }

        $model->update(['ten_danh_muc' => $data['ten_danh_muc']]);

        return $model->fresh();
    }

    public function toggleStatus(int $id, int $status): void
    {
        $model = NewsCategory::query()->findOrFail($id);
        $model->update(['is_delete' => $status]);
    }
}
