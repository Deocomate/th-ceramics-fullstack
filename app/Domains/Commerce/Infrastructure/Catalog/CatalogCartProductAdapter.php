<?php

namespace App\Domains\Commerce\Infrastructure\Catalog;

use App\Domains\Catalog\Application\Ports\CatalogQueryPort;
use App\Domains\Commerce\Application\Ports\CatalogCartProductPort;

class CatalogCartProductAdapter implements CatalogCartProductPort
{
    public function __construct(private readonly CatalogQueryPort $catalog) {}

    public function details(string $productType, int $productId, ?int $variantId): array
    {
        return $this->catalog->cartDetails($productType, $productId, $variantId);
    }
}
