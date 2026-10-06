<?php

namespace App\Domains\Catalog\Application\Ports;

interface CatalogQueryPort
{
    public function all(string $type, string $status = 'active', ?string $categoryType = null): mixed;

    public function find(string $type, int $publicId): mixed;

    public function findActive(string $type, int $publicId): mixed;

    public function paginate(
        string $type,
        array $filters,
        int $perPage = 8,
        ?string $categoryType = null,
        string $pageName = 'page'
    ): mixed;

    public function filtered(string $type, array $filters, ?string $categoryType = null): mixed;

    public function related(string $type, int $publicId, ?string $categoryType, int $limit = 4): mixed;

    public function forHome(string $type, int $limit = 8): mixed;

    public function search(string $keyword, int $limit = 8): mixed;

    /**
     * @return array{name: string, variant_name: ?string, sku: ?string, price: int, image: ?string}
     */
    public function cartDetails(string $type, int $productId, ?int $variantId): array;

    public function cartOptions(string $type, int $productId): array;
}
