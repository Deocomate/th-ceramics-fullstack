<?php

namespace App\Services;

use App\Models\PhanLoaiPhuKienNgoiCt;
use InvalidArgumentException;

class PhanLoaiPhuKienNgoiCtService
{
    public function __construct(private readonly GlobalProductCodeService $globalCodeService) {}

    public function getAll(string $status = 'active', ?int $productId = null, ?string $categoryType = null)
    {
        $query = PhanLoaiPhuKienNgoiCt::query()
            ->with('product')
            ->latest();

        if ($productId) {
            $query->where('phu_kien_ngoi_ct_id', $productId);
        }

        if ($categoryType) {
            $query->whereHas('product', fn ($productQuery) => $productQuery->where('category_type', $categoryType));
        }

        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }

        return $query->get();
    }

    public function create(array $data): PhanLoaiPhuKienNgoiCt
    {
        if (! $this->globalCodeService->isUnique($data['code'])) {
            throw new InvalidArgumentException('Mã code đã tồn tại trong hệ thống.');
        }

        if (PhanLoaiPhuKienNgoiCt::query()->where('name', $data['name'])->exists()) {
            throw new InvalidArgumentException('Tên phân loại đã tồn tại.');
        }

        return PhanLoaiPhuKienNgoiCt::query()->create([
            'name' => $data['name'],
            'code' => $data['code'],
            'price' => $data['price'],
            'phu_kien_ngoi_ct_id' => $data['phu_kien_ngoi_ct_id'],
            'is_delete' => 0,
        ]);
    }

    public function update(int $id, array $data): PhanLoaiPhuKienNgoiCt
    {
        $model = PhanLoaiPhuKienNgoiCt::query()->findOrFail($id);

        if (! $this->globalCodeService->isUnique($data['code'], 'phan_loai_phu_kien_ngoi_ct', $model->phan_loai_phu_kien_ngoi_ct_id)) {
            throw new InvalidArgumentException('Mã code đã tồn tại trong hệ thống.');
        }

        if (PhanLoaiPhuKienNgoiCt::query()
            ->where('name', $data['name'])
            ->where('phan_loai_phu_kien_ngoi_ct_id', '!=', $id)
            ->exists()) {
            throw new InvalidArgumentException('Tên phân loại đã tồn tại.');
        }

        $model->fill([
            'name' => $data['name'],
            'code' => $data['code'],
            'price' => $data['price'],
            'phu_kien_ngoi_ct_id' => $data['phu_kien_ngoi_ct_id'],
        ])->save();

        return $model->fresh();
    }

    public function toggleStatus(int $id, int $status): void
    {
        PhanLoaiPhuKienNgoiCt::query()->findOrFail($id)->fill(['is_delete' => $status])->save();
    }
}
