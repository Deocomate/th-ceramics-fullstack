<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\MauSacNgoiHaiCoCt;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

class MauSacNgoiHaiCoCtService
{
    public function __construct(private readonly GlobalProductCodeService $globalCodeService) {}

    public function getAll(string $status = 'active', ?int $productId = null)
    {
        $query = MauSacNgoiHaiCoCt::query()->with('product')->latest();

        if ($status === 'active') {
            $query->where('is_delete', 0);
        } elseif ($status === 'deleted') {
            $query->where('is_delete', 1);
        }

        if ($productId) {
            $query->where('ngoi_hai_co_ct_id', $productId);
        }

        return $query->get();
    }

    public function create(array $data)
    {
        if (! $this->globalCodeService->isUnique($data['code'])) {
            throw new InvalidArgumentException('Mã sản phẩm (Code) đã tồn tại trên hệ thống.');
        }

        $fillable = [
            'name' => $data['name'],
            'code' => $data['code'],
            'price' => $data['price'],
            'ngoi_hai_co_ct_id' => $data['ngoi_hai_co_ct_id'],
            'is_delete' => 0,
        ];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $fillable['image'] = FileUploadHelper::upload($data['image'], 'mau_sac_ngoi_hai_co_ct/images');
        }

        return MauSacNgoiHaiCoCt::query()->create($fillable);
    }

    public function update(int $id, array $data)
    {
        /** @var MauSacNgoiHaiCoCt $model */
        $model = MauSacNgoiHaiCoCt::query()->findOrFail($id);

        if (! $this->globalCodeService->isUnique($data['code'], 'mau_sac_ngoi_hai_co_ct', $model->mau_sac_ngoi_hai_co_ct_id)) {
            throw new InvalidArgumentException('Mã sản phẩm (Code) này đã được sử dụng.');
        }

        $fillable = [
            'name' => $data['name'],
            'code' => $data['code'],
            'price' => $data['price'],
            'ngoi_hai_co_ct_id' => $data['ngoi_hai_co_ct_id'],
        ];

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $fillable['image'] = FileUploadHelper::replace($data['image'], $model->image, 'mau_sac_ngoi_hai_co_ct/images');
        }

        $model->fill($fillable)->save();

        return $model->fresh();
    }

    public function toggleStatus(int $id, int $status): void
    {
        /** @var MauSacNgoiHaiCoCt $model */
        $model = MauSacNgoiHaiCoCt::query()->findOrFail($id);
        $model->fill(['is_delete' => $status])->save();
    }
}
