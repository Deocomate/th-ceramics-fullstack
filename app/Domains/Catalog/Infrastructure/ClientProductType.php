<?php

namespace App\Domains\Catalog\Infrastructure;

use App\Domains\Catalog\ProductTypeRegistry;
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
        if (! $config || ! ($config['has_variants'] ?? false)) {
            return null;
        }

        $relation = $config['relation'] ?? 'variants';
        $pk = $config['variant_pk'] ?? 'public_id';

        return ['relation' => $relation, 'pk' => $pk];
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

        $id = data_get($first, $pk)
            ?? data_get($first, 'public_id')
            ?? data_get($first, 'phan_loai_lan_can_gom_su_ct_id')
            ?? data_get($first, 'mau_sac_ngoi_hai_van_mieu_ct_id')
            ?? data_get($first, 'mau_sac_ngoi_hai_co_ct_id')
            ?? data_get($first, 'phan_loai_den_vuon_gom_su_ct_id')
            ?? data_get($first, 'phan_loai_phu_kien_ngoi_ct_id')
            ?? data_get($first, 'id');

        return $id !== null ? (int) $id : null;
    }

    private static function activeVariants(object $product, string $relation): Collection
    {
        if (isset($product->phanLoais)) {
            return collect($product->phanLoais)->filter(fn ($variant) => ! (bool) data_get($variant, 'is_delete', false))->values();
        }

        if ($relation !== 'variants') {
            if (method_exists($product, 'relationLoaded') && $product->relationLoaded($relation)) {
                return collect($product->{$relation})->filter(fn ($variant) => ! (bool) data_get($variant, 'is_delete', false))->values();
            }

            if (isset($product->{$relation})) {
                return collect($product->{$relation})->filter(fn ($variant) => ! (bool) data_get($variant, 'is_delete', false))->values();
            }

            if (method_exists($product, $relation)) {
                return $product->{$relation}()->where('is_delete', false)->get();
            }
        }

        if (method_exists($product, 'relationLoaded') && $product->relationLoaded('variants')) {
            $filtered = collect($product->variants)->filter(fn ($variant) => ! (bool) data_get($variant, 'is_delete', false));
            $nonDefault = $filtered->where('is_default', false);

            return $nonDefault->isNotEmpty() ? $nonDefault->values() : $filtered->values();
        }

        if (method_exists($product, 'variants')) {
            $all = $product->variants()->where('is_delete', false)->get();
            $nonDefault = $all->where('is_default', false);

            return $nonDefault->isNotEmpty() ? $nonDefault->values() : $all->values();
        }

        return collect();
    }
}
