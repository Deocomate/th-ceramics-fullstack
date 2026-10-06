<?php

namespace App\Domains\Commerce\Infrastructure\Catalog;

use App\Domains\Catalog\Application\Ports\CatalogQueryPort;
use App\Domains\Commerce\Application\Ports\ProductCartOptionsPort;

class CatalogProductCartOptionsAdapter implements ProductCartOptionsPort
{
    public function __construct(private readonly CatalogQueryPort $catalog) {}

    public function get(string $productType, int $productId): array
    {
        return $this->catalog->cartOptions($productType, $productId);
    }
}
