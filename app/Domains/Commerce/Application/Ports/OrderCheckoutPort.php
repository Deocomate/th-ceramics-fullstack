<?php

namespace App\Domains\Commerce\Application\Ports;

interface OrderCheckoutPort
{
    /** @param array<string, mixed> $order @param array<int, array<string, mixed>> $items */
    public function create(array $order, array $items, ?string $couponCode): string;
}
