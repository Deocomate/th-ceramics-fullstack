<?php
namespace App\Services;

use App\Models\PhanLoaiBoNocChuVanCt;
use InvalidArgumentException;
use Illuminate\Validation\ValidationException;

class PhanLoaiBoNocChuVanCtService
{
    public function __construct(private readonly GlobalProductCodeService $globalCodeService) {}

    public function getAll(string $status = 'active', ?int $productId = null)
    {
        $query = PhanLoaiBoNocChuVanCt::query()->with('product')->latest();

        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }

        if ($productId) {
            $query->where('bo_noc_chu_van_ct_id', $productId);
        }

        return $query->get();
    }

    public function create(array $data)
    {
        if (!$this->globalCodeService->isUnique($data['code'])) {
            throw new InvalidArgumentException('Mã sản phẩm (Code) đã tồn tại trên hệ thống.');
        }

        if (PhanLoaiBoNocChuVanCt::query()->where('name', $data['name'])->exists()) {
            throw ValidationException::withMessages(['name' => 'Tên phân loại này đã tồn tại.']);
        }

        $fillable = [
            'name'                 => $data['name'],
            'code'                 => $data['code'],
            'price'                => $data['price'],
            'bo_noc_chu_van_ct_id' => $data['bo_noc_chu_van_ct_id'],
            'is_delete'            => 0,
        ];

        return PhanLoaiBoNocChuVanCt::query()->create($fillable);
    }

    public function update(int $id, array $data)
    {
        $model = PhanLoaiBoNocChuVanCt::query()->findOrFail($id);

        if (!$this->globalCodeService->isUnique($data['code'], 'phan_loai_bo_noc_chu_van_ct', $model->phan_loai_bo_noc_chu_van_ct_id)) {
            throw new InvalidArgumentException('Mã sản phẩm (Code) này đã được sử dụng.');
        }

        if (PhanLoaiBoNocChuVanCt::query()->where('name', $data['name'])->where('phan_loai_bo_noc_chu_van_ct_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['name' => 'Tên phân loại này đã tồn tại.']);
        }

        $fillable =[
            'name'                 => $data['name'],
            'code'                 => $data['code'],
            'price'                => $data['price'],
            'bo_noc_chu_van_ct_id' => $data['bo_noc_chu_van_ct_id'],
        ];

        $model->fill($fillable)->save();
        return $model->fresh();
    }

    public function toggleStatus(int $id, int $status): void
    {
        $model = PhanLoaiBoNocChuVanCt::query()->findOrFail($id);
        $model->fill(['is_delete' => $status])->save();
    }
}