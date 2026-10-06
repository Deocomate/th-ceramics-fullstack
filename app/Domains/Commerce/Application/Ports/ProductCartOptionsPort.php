<?php

namespace App\Domains\Commerce\Application\Ports;

interface ProductCartOptionsPort
{
    /** @return array<string, mixed> */
    public function get(string $productType, int $productId): array;
}
