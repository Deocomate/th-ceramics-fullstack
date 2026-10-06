<?php

namespace App\Domains\Commerce\Domain;

use DateTimeImmutable;

final class CouponDiscountCalculator
{
    /** @param array<string, mixed> $coupon @param array<int, array<string, mixed>> $cartItems */
    public function calculate(array $coupon, array $cartItems, DateTimeImmutable $now): array
    {
        if (! $coupon['is_active'] || $coupon['is_delete']) {
            return $this->invalid('Mã giảm giá đã bị vô hiệu hóa.');
        }

        if ($now < new DateTimeImmutable($coupon['start_date'])) {
            return $this->invalid('Mã giảm giá chưa có hiệu lực.');
        }
        if ($coupon['end_date'] !== null && $now > new DateTimeImmutable($coupon['end_date'])) {
            return $this->invalid('Mã giảm giá đã hết hạn.');
        }
        if ($coupon['usage_limit'] !== null && $coupon['used_count'] >= $coupon['usage_limit']) {
            return $this->invalid('Mã giảm giá đã hết lượt sử dụng.');
        }

        $applicableTypes = $coupon['applicable_product_types'];
        $applicableSubtotal = 0;
        foreach ($cartItems as $item) {
            if ($applicableTypes === null || in_array($item['product_type'] ?? null, $applicableTypes, true)) {
                $applicableSubtotal += (int) $item['price'] * (int) $item['quantity'];
            }
        }

        if ($applicableSubtotal <= 0) {
            return $this->invalid('Không có sản phẩm nào trong giỏ hàng được áp dụng mã này.');
        }

        if ($coupon['min_order_value'] > 0 && $applicableSubtotal < $coupon['min_order_value']) {
            $formattedMin = number_format((int) $coupon['min_order_value'], 0, ',', '.');

            return $this->invalid("Đơn hàng chưa đạt giá trị tối thiểu {$formattedMin}đ.");
        }

        if ($coupon['discount_type'] === 'percent') {
            $discount = (int) round(($applicableSubtotal * $coupon['discount_value']) / 100);
            if ($coupon['max_discount_amount'] !== null) {
                $discount = min($discount, (int) $coupon['max_discount_amount']);
            }
        } else {
            $discount = min((int) $coupon['discount_value'], $applicableSubtotal);
        }

        return [
            'valid' => true,
            'discount' => $discount,
            'message' => 'Áp dụng mã giảm giá thành công.',
        ];
    }

    private function invalid(string $message): array
    {
        return ['valid' => false, 'discount' => 0, 'message' => $message];
    }
}
