<?php

namespace App\Domains\Commerce\Infrastructure\Coupons;

use App\Domains\Commerce\Application\Ports\CouponRepositoryPort;
use App\Domains\Commerce\Domain\CouponDiscountCalculator;
use App\Domains\Commerce\Domain\CouponProductTypes;
use App\Domains\Commerce\Infrastructure\Models\Coupon;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use Illuminate\Http\UploadedFile;

class EloquentCouponRepository implements CouponRepositoryPort
{
    public static function productTypes(): array
    {
        return CouponProductTypes::all();
    }

    // ──────────────────────────────────────
    // CRUD (Phase 2)
    // ──────────────────────────────────────

    public function getAll(): mixed
    {
        return Coupon::query()
            ->where('is_delete', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getDeleted(): mixed
    {
        return Coupon::query()
            ->where('is_delete', 1)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
    }

    public function findById(int $id): mixed
    {
        return Coupon::where('is_delete', 0)->findOrFail($id);
    }

    public function findDeletedById(int $id): mixed
    {
        return Coupon::where('is_delete', 1)->findOrFail($id);
    }

    public function store(array $data): mixed
    {
        if (isset($data['banner_image']) && $data['banner_image'] instanceof UploadedFile) {
            $data['banner_image'] = FileUploadHelper::upload($data['banner_image'], 'coupons');
        } else {
            unset($data['banner_image']);
        }

        return Coupon::create($data);
    }

    public function update(int $id, array $data): mixed
    {
        $model = $this->findById($id);

        if (isset($data['banner_image']) && $data['banner_image'] instanceof UploadedFile) {
            $data['banner_image'] = FileUploadHelper::replace(
                $data['banner_image'],
                $model->banner_image,
                'coupons'
            );
        } else {
            unset($data['banner_image']);
        }

        $model->fill($data)->save();

        return $model->fresh();
    }

    public function destroy(int $id): void
    {
        $model = $this->findById($id);
        $model->update(['is_delete' => 1]);
    }

    public function restore(int $id): void
    {
        $this->findDeletedById($id)->update(['is_delete' => 0]);
    }

    public function forceDelete(int $id): void
    {
        $model = $this->findById($id);
        FileUploadHelper::delete($model->banner_image);
        $model->delete();
    }

    // ──────────────────────────────────────
    // Validation & Calculation (Phase 3)
    // ──────────────────────────────────────

    public function validateAndCalculate(string $code, array $cartItems): array
    {
        $coupon = Coupon::query()
            ->where('code', $code)
            ->where('is_delete', 0)
            ->first();

        if (! $coupon) {
            return ['valid' => false, 'discount' => 0, 'message' => 'Mã giảm giá không tồn tại.'];
        }
        $result = (new CouponDiscountCalculator)->calculate(
            $coupon->toArray(),
            $cartItems,
            new \DateTimeImmutable(now()->toIso8601String()),
        );

        return $result['valid'] ? [...$result, 'coupon' => $coupon] : $result;
    }

    public function incrementUsage(string $code): void
    {
        Coupon::where('code', $code)->increment('used_count');
    }

    public function decrementUsage(string $code): void
    {
        Coupon::where('code', $code)->where('used_count', '>', 0)->decrement('used_count');
    }

    public function getCartSubtotal(array $cartItems): int
    {
        return (int) collect($cartItems)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }
}
