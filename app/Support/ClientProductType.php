<?php

namespace App\Support;

use App\Products\ProductTypeRegistry;
use Illuminate\Support\Collection;

final class ClientProductType
{
    public static function fromDetailRoute(?string $routeName): ?string
    {
        return ProductTypeRegistry::fromRoute($routeName);
    }

    public static function primaryKeyField(?string $productType, ?string $routeName = null): ?string
    {
        $type = $productType ?: ProductTypeRegistry::fromRoute($routeName);

        return $type ? (ProductTypeRegistry::get($type)['pk'] ?? null) : null;
    }

    public static function resolveProductId(
        ?object $product,
        ?string $productType = null,
        ?string $pkField = null,
        mixed $explicitId = null,
    ): ?int {
        if ($explicitId !== null && $explicitId !== '') {
            return (int) $explicitId;
        }

        if (! $product) {
            return null;
        }

        $field = $pkField ?: self::primaryKeyField($productType);

        if ($field) {
            $value = data_get($product, $field);

            return $value !== null ? (int) $value : null;
        }

        if (method_exists($product, 'getKey')) {
            $key = $product->getKey();

            return $key !== null ? (int) $key : null;
        }

        return null;
    }

    /** @return array{relation: string, pk: string}|null */
    public static function variantRelationConfig(?string $productType): ?array
    {
        $config = $productType ? ProductTypeRegistry::get($productType) : null;
        if (! $config || ! $config['relation']) {
            return null;
        }

        return ['relation' => $config['relation'], 'pk' => $config['variant_pk']];
    }

    public static function requiresVariantSelection(string $productType): bool
    {
        return ProductTypeRegistry::get($productType)['requires_variant'] ?? false;
    }

    public static function resolveFirstVariantId(?object $product, ?string $productType): ?int
    {
        $config = self::variantRelationConfig($productType);
        if (! $product || ! $config) {
            return null;
        }

        $relation = $config['relation'];
        $pk = $config['pk'];

        $variants = self::activeVariants($product, $relation);

        if ($variants->isEmpty()) {
            return null;
        }

        $first = $variants->sortBy(fn ($variant) => (float) data_get($variant, 'price', 0))->first();

        $id = data_get($first, $pk);

        return $id !== null ? (int) $id : null;
    }

    private static function activeVariants(object $product, string $relation): Collection
    {
        if (method_exists($product, 'relationLoaded') && $product->relationLoaded($relation)) {
            return collect($product->{$relation})->filter(fn ($variant) => (int) data_get($variant, 'is_delete', 0) === 0)->values();
        }

        if (isset($product->{$relation})) {
            return collect($product->{$relation})->filter(fn ($variant) => (int) data_get($variant, 'is_delete', 0) === 0)->values();
        }

        if (method_exists($product, $relation)) {
            return $product->{$relation}()->where('is_delete', 0)->get();
        }

        return collect();
    }
}
