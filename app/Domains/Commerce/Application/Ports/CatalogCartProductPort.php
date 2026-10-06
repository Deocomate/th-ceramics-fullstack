<?php

namespace App\Domains\Commerce\Application\Ports;

interface CatalogCartProductPort
{
    /** @return array{name: string, variant_name: ?string, sku: ?string, price: int, image: ?string} */
    public function details(string $productType, int $productId, ?int $variantId): array;
}
