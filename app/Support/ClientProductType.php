<?php

namespace App\Support;

use Illuminate\Support\Collection;

final class ClientProductType
{
    /** @var array<string, array{type: string, pk: string}> */
    private const ROUTE_MAP = [
        'client.products.ngoi-am-duong.detail' => ['type' => 'ngoi_am_duong_ct', 'pk' => 'ngoi_am_duong_ct_id'],
        'client.products.ngoi-hai-van-mieu.detail' => ['type' => 'ngoi_hai_van_mieu_ct', 'pk' => 'ngoi_hai_van_mieu_ct_id'],
        'client.products.ngoi-hai-co.detail' => ['type' => 'ngoi_hai_co_ct', 'pk' => 'ngoi_hai_co_ct_id'],
        'client.products.gach-hoa-thong-gio.detail' => ['type' => 'gach_hoa_thong_gio_ct', 'pk' => 'gach_hoa_thong_gio_ct_id'],
        'client.products.gach-trang-tri.detail' => ['type' => 'gach_trang_tri_ct', 'pk' => 'gach_trang_tri_ct_id'],
        'client.products.gach-co-bat-trang.detail' => ['type' => 'gach_co_bat_trang_ct', 'pk' => 'gach_co_bat_trang_ct_id'],
        'client.products.linh-vat-phong-thuy.detail' => ['type' => 'linh_vat_phong_thuy_ct', 'pk' => 'linh_vat_phong_thuy_ct_id'],
        'client.products.lan-can-gom-su.detail' => ['type' => 'lan_can_gom_su_ct', 'pk' => 'lan_can_gom_su_ct_id'],
        'client.products.den-gom-su.detail' => ['type' => 'den_vuon_gom_su_ct', 'pk' => 'den_vuon_gom_su_ct_id'],
        'client.products.phu-kien-ngoi.ngoi-bo-noc.detail' => ['type' => 'phu_kien_ngoi_ct', 'pk' => 'phu_kien_ngoi_ct_id'],
        'client.products.phu-kien-ngoi.bo-noc-chu-van.detail' => ['type' => 'phu_kien_ngoi_ct', 'pk' => 'phu_kien_ngoi_ct_id'],
        'client.products.phu-kien-ngoi.detail' => ['type' => 'phu_kien_ngoi_ct', 'pk' => 'phu_kien_ngoi_ct_id'],
    ];

    /** @var array<string, array{relation: string, pk: string}> */
    private const VARIANT_RELATIONS = [
        'lan_can_gom_su_ct' => ['relation' => 'phanLoais', 'pk' => 'phan_loai_lan_can_gom_su_ct_id'],
        'den_vuon_gom_su_ct' => ['relation' => 'phanLoais', 'pk' => 'phan_loai_den_vuon_gom_su_ct_id'],
        'phu_kien_ngoi_ct' => ['relation' => 'phanLoais', 'pk' => 'phan_loai_phu_kien_ngoi_ct_id'],
        'ngoi_hai_van_mieu_ct' => ['relation' => 'mauSacs', 'pk' => 'mau_sac_ngoi_hai_van_mieu_ct_id'],
        'ngoi_hai_co_ct' => ['relation' => 'mauSacs', 'pk' => 'mau_sac_ngoi_hai_co_ct_id'],
    ];

    public static function fromDetailRoute(?string $routeName): ?string
    {
        if (! $routeName) {
            return null;
        }

        return self::ROUTE_MAP[$routeName]['type'] ?? null;
    }

    public static function primaryKeyField(?string $productType, ?string $routeName = null): ?string
    {
        if ($productType) {
            foreach (self::ROUTE_MAP as $config) {
                if ($config['type'] === $productType) {
                    return $config['pk'];
                }
            }
        }

        if ($routeName && isset(self::ROUTE_MAP[$routeName])) {
            return self::ROUTE_MAP[$routeName]['pk'];
        }

        return null;
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
        if (! $productType || ! isset(self::VARIANT_RELATIONS[$productType])) {
            return null;
        }

        return self::VARIANT_RELATIONS[$productType];
    }

    public static function requiresVariantSelection(string $productType): bool
    {
        return in_array($productType, [
            'phu_kien_ngoi_ct',
            'lan_can_gom_su_ct',
            'den_vuon_gom_su_ct',
            'ngoi_hai_co_ct',
        ], true);
    }

    public static function resolveFirstVariantId(?object $product, ?string $productType): ?int
    {
        if (! $product || ! $productType || ! isset(self::VARIANT_RELATIONS[$productType])) {
            return null;
        }

        $config = self::VARIANT_RELATIONS[$productType];
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
