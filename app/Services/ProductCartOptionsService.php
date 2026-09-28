<?php

namespace App\Services;

use App\Domains\Catalog\Services\CatalogQueryService;

class ProductCartOptionsService
{
    public function __construct(private readonly CatalogQueryService $queryService) {}

    public function getOptions(string $productType, int $productId): array
    {
        return $this->queryService->cartOptions($productType, $productId);
    }
}
