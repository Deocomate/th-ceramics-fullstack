<?php

namespace App\Domains\Commerce\Application\Ports;

interface CartSessionPort
{
    public function cart(): array;

    public function saveCart(array $cart): void;

    public function clearCart(): void;

    public function couponCode(): ?string;

    public function setCouponCode(string $code): void;

    public function clearCouponCode(): void;
}
