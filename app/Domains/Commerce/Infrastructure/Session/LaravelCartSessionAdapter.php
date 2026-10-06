<?php

namespace App\Domains\Commerce\Infrastructure\Session;

use App\Domains\Commerce\Application\Ports\CartSessionPort;

class LaravelCartSessionAdapter implements CartSessionPort
{
    private const CART_KEY = 'th_cart';

    private const COUPON_KEY = 'th_coupon_code';

    public function cart(): array
    {
        return session()->get(self::CART_KEY, []);
    }

    public function saveCart(array $cart): void
    {
        session()->put(self::CART_KEY, $cart);
    }

    public function clearCart(): void
    {
        session()->forget(self::CART_KEY);
    }

    public function couponCode(): ?string
    {
        return session()->get(self::COUPON_KEY);
    }

    public function setCouponCode(string $code): void
    {
        session()->put(self::COUPON_KEY, $code);
    }

    public function clearCouponCode(): void
    {
        session()->forget(self::COUPON_KEY);
    }
}
