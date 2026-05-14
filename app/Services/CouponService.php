<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\Coupon;
use Illuminate\Http\UploadedFile;

class CouponService
{
    private const PRODUCT_TYPES = [
        'ngoi_am_duong_ct' => 'Ngói Âm Dương',
        'ngoi_hai_van_mieu_ct' => 'Ngói Hài Văn Miếu',
        'ngoi_hai_co_ct' => 'Ngói Hài Cổ',
        'gach_hoa_thong_gio_ct' => 'Gạch Hoa Thông Gió',
        'gach_trang_tri_ct' => 'Gạch Trang Trí',
        'gach_co_bat_trang_ct' => 'Gạch Cổ Bát Tràng',
        'linh_vat_phong_thuy_ct' => 'Linh Vật Phong Thủy',
        'lan_can_gom_xu' => 'Lan Can Gốm Sứ',
        'den_gom_su' => 'Đèn Gốm Sứ',
        'den_vuon_gom_su_ct' => 'Đèn Gốm Sứ Chi Tiết',
        'phu_kien_ngoi' => 'Phụ Kiện Ngói',
    ];

    public static function productTypes(): array
    {
        return self::PRODUCT_TYPES;
    }

    // ──────────────────────────────────────
    // CRUD (Phase 2)
    // ──────────────────────────────────────

    public function getAll()
    {
        return Coupon::query()
            ->where('is_delete', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getDeleted()
    {
        return Coupon::query()
            ->where('is_delete', 1)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
    }

    public function findById(int $id): Coupon
    {
        return Coupon::where('is_delete', 0)->findOrFail($id);
    }

    public function findDeletedById(int $id): Coupon
    {
        return Coupon::where('is_delete', 1)->findOrFail($id);
    }

    public function store(array $data): Coupon
    {
        if (isset($data['banner_image']) && $data['banner_image'] instanceof UploadedFile) {
            $data['banner_image'] = FileUploadHelper::upload($data['banner_image'], 'coupons');
        } else {
            unset($data['banner_image']);
        }

        return Coupon::create($data);
    }

    public function update(int $id, array $data): Coupon
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

        if (! $coupon->isValid()) {
            if (! $coupon->is_active) {
                return ['valid' => false, 'discount' => 0, 'message' => 'Mã giảm giá đã bị vô hiệu hóa.'];
            }

            $now = now();
            if ($now->lt($coupon->start_date)) {
                return ['valid' => false, 'discount' => 0, 'message' => 'Mã giảm giá chưa có hiệu lực.'];
            }
            if ($coupon->end_date && $now->gt($coupon->end_date)) {
                return ['valid' => false, 'discount' => 0, 'message' => 'Mã giảm giá đã hết hạn.'];
            }

            return ['valid' => false, 'discount' => 0, 'message' => 'Mã giảm giá đã hết lượt sử dụng.'];
        }

        $applicableProductTypes = $coupon->applicable_product_types;
        $applicableSubtotal = 0;

        foreach ($cartItems as $item) {
            if ($applicableProductTypes === null || in_array($item['product_type'], $applicableProductTypes, true)) {
                $applicableSubtotal += $item['price'] * $item['quantity'];
            }
        }

        if ($applicableSubtotal <= 0) {
            return ['valid' => false, 'discount' => 0, 'message' => 'Không có sản phẩm nào trong giỏ hàng được áp dụng mã này.'];
        }

        if ($coupon->min_order_value > 0 && $applicableSubtotal < $coupon->min_order_value) {
            $formattedMin = number_format($coupon->min_order_value, 0, ',', '.');

            return ['valid' => false, 'discount' => 0, 'message' => "Đơn hàng chưa đạt giá trị tối thiểu {$formattedMin}đ."];
        }

        $discount = 0;

        if ($coupon->discount_type === 'percent') {
            $discount = (int) round(($applicableSubtotal * $coupon->discount_value) / 100);
            if ($coupon->max_discount_amount !== null && $discount > $coupon->max_discount_amount) {
                $discount = $coupon->max_discount_amount;
            }
        } else {
            $discount = min((int) $coupon->discount_value, $applicableSubtotal);
        }

        return [
            'valid' => true,
            'discount' => $discount,
            'message' => 'Áp dụng mã giảm giá thành công.',
            'coupon' => $coupon,
        ];
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
