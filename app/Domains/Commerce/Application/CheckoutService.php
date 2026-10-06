<?php

namespace App\Domains\Commerce\Application;

use App\Domains\Commerce\Application\Ports\CartSessionPort;
use App\Domains\Commerce\Application\Ports\OrderCheckoutPort;

class CheckoutService
{
    public function __construct(
        private readonly CouponService $coupons,
        private readonly OrderCheckoutPort $orders,
        private readonly CartSessionPort $cartSession,
    ) {}

    /** @param array<string, mixed> $customer @param array<int, array<string, mixed>> $items */
    public function placeOrder(array $customer, array $items, ?string $couponCode, ?int $userId): ?string
    {
        if ($items === []) {
            return null;
        }

        $discount = 0;
        if ($couponCode !== null) {
            $couponResult = $this->coupons->validateAndCalculate($couponCode, $items);
            if ($couponResult['valid']) {
                $discount = $couponResult['discount'];
            } else {
                $couponCode = null;
                $this->cartSession->clearCouponCode();
            }
        }

        $subtotal = array_sum(array_column($items, 'total'));
        $shippingFee = 0;

        return $this->orders->create([
            'user_id' => $userId,
            'customer_name' => $customer['customer_name'],
            'phone' => $customer['phone'],
            'email' => $customer['email'] ?? null,
            'address' => $customer['address'],
            'note' => $customer['note'] ?? null,
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'discount' => $discount,
            'total_amount' => max(0, $subtotal - $discount + $shippingFee),
            'payment_method' => $customer['payment_method'],
            'coupon_code' => $couponCode,
        ], $items, $couponCode);
    }
}
