<?php

namespace App\Domains\Catalog\Infrastructure\Services;

use App\Domains\Catalog\Infrastructure\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UnifiedProductCatalog
{
    public function __construct(private readonly CatalogQueryService $queryService) {}

    /** @return Collection<int, Product> */
    public function all(string $type, string $status = 'active', ?string $categoryType = null): Collection
    {
        return $this->queryService->all($type, $status, $categoryType);
    }

    public function find(string $type, int $legacyId): Product
    {
        return $this->queryService->find($type, $legacyId);
    }

    public function findByLegacyReference(string $type, int $legacyReference, string $categoryType): ?Product
    {
        return Product::query()
            ->with(['variants.publicId', 'media', 'publicId'])
            ->where('type_key', $type)
            ->where('category_type', $categoryType)
            ->where(function ($query) use ($type, $legacyReference) {
                $query->where('legacy_id', $legacyReference)
                    ->orWhereHas('publicId', fn ($ids) => $ids->where('type_key', $type)
                        ->where('public_id', $legacyReference));
            })->first();
    }

    public function paginate(
        string $type,
        array $filters,
        int $perPage = 8,
        ?string $categoryType = null,
        string $pageName = 'page'
    ): LengthAwarePaginator {
        return $this->queryService->paginate($type, $filters, $perPage, $categoryType, $pageName);
    }

    /** @return Collection<int, Product> */
    public function filtered(string $type, array $filters, ?string $categoryType = null): Collection
    {
        return $this->queryService->filtered($type, $filters, $categoryType);
    }

    /** @return Collection<int, Product> */
    public function related(string $type, int $legacyId, ?string $categoryType, int $limit): Collection
    {
        return $this->queryService->related($type, $legacyId, $categoryType, $limit);
    }

    /** @return \Illuminate\Support\Collection<int, array<string, mixed>> */
    public function search(string $keyword, int $limit = 8): \Illuminate\Support\Collection
    {
        return $this->queryService->search($keyword, $limit);
    }

    public function cartDetails(string $type, int $legacyId, ?int $legacyVariantId): array
    {
        return $this->queryService->cartDetails($type, $legacyId, $legacyVariantId);
    }

    public function cartOptions(string $type, int $legacyId): array
    {
        return $this->queryService->cartOptions($type, $legacyId);
    }

    public function project(Product $product): Product
    {
        return $product;
    }
}
