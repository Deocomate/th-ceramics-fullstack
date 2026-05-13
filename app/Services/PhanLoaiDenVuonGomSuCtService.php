<?php
namespace App\Services;
use App\Models\PhanLoaiDenVuonGomSuCt;
use InvalidArgumentException;
use Illuminate\Validation\ValidationException;

class PhanLoaiDenVuonGomSuCtService
{
    public function __construct(private readonly GlobalProductCodeService $globalCodeService) {}
    public function getAll(string $status = 'active', ?int $productId = null) {
        $query = PhanLoaiDenVuonGomSuCt::with('product')->latest();
        if ($status === 'active') $query->where('is_delete', 0);
        elseif ($status === 'deleted') $query->where('is_delete', 1);
        if ($productId) $query->where('den_vuon_gom_su_ct_id', $productId);
        return $query->get();
    }
    public function create(array $data) {
        if (!$this->globalCodeService->isUnique($data['code'])) {
            throw new InvalidArgumentException('Mã sản phẩm (Code) đã tồn tại.');
        }
        if (PhanLoaiDenVuonGomSuCt::where('name', $data['name'])->exists()) {
            throw ValidationException::withMessages(['name' => 'Tên phân loại này đã tồn tại.']);
        }
        $data['is_delete'] = 0;
        return PhanLoaiDenVuonGomSuCt::create($data);
    }
    public function update(int $id, array $data) {
        $model = PhanLoaiDenVuonGomSuCt::findOrFail($id);
        if (!$this->globalCodeService->isUnique($data['code'], 'phan_loai_den_vuon_gom_su_ct', $id)) {
            throw new InvalidArgumentException('Mã sản phẩm (Code) này đã được sử dụng.');
        }
        if (PhanLoaiDenVuonGomSuCt::where('name', $data['name'])->where('phan_loai_den_vuon_gom_su_ct_id', '!=', $id)->exists()) {
            throw ValidationException::withMessages(['name' => 'Tên phân loại này đã tồn tại.']);
        }
        $model->update($data);
        return $model->fresh();
    }
    public function toggleStatus(int $id, int $status): void {
        PhanLoaiDenVuonGomSuCt::findOrFail($id)->update(['is_delete' => $status]);
    }
}