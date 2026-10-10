<?php

namespace App\Domains\Commerce\Infrastructure\Catalog;

use App\Domains\Catalog\Domain\ProductTypeRegistry;
use App\Domains\Commerce\Application\Ports\SellableProductTypesPort;

class CatalogSellableProductTypesAdapter implements SellableProductTypesPort
{
    public function labels(): array
    {
        return array_map(static fn (array $config) => $config['label'], ProductTypeRegistry::all());
    }
}
