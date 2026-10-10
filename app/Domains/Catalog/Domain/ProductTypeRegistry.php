<?php

namespace App\Domains\Catalog\Domain;

final class ProductTypeRegistry
{
    /** Rules shared by every type unless its own row overrides them. */
    private const DEFAULTS = [
        'has_categories' => false,
        'list_by_category' => false,
        'options_as_variants' => false,
        'code_source' => 'default',
        'code_falls_back_to_default' => false,
        'code_prefixes' => [],
        'code_empty' => '',
        'variant_label' => 'Phân loại',
        'price_source' => 'default',
        'price_format' => '%s đ',
        'price_empty' => 'Liên hệ',
    ];

    /** @var array<string, array<string, mixed>> */
    private const TYPES = [
        'ngoi_am_duong_ct' => ['pk' => 'ngoi_am_duong_ct_id', 'label' => 'Ngói Âm Dương', 'route' => 'client.products.ngoi-am-duong.detail', 'has_variants' => false, 'requires_variant' => false, 'variant_table' => null, 'variant_label' => 'Màu sắc', 'options_as_variants' => true],
        'ngoi_hai_van_mieu_ct' => ['pk' => 'ngoi_hai_van_mieu_ct_id', 'label' => 'Ngói Hài Văn Miếu', 'route' => 'client.products.ngoi-hai-van-mieu.detail', 'has_variants' => true, 'requires_variant' => false, 'variant_table' => 'mau_sac_ngoi_hai_van_mieu_ct', 'variant_label' => 'Màu sắc'],
        'ngoi_hai_co_ct' => ['pk' => 'ngoi_hai_co_ct_id', 'label' => 'Ngói Hài Cổ', 'route' => 'client.products.ngoi-hai-co.detail', 'has_variants' => true, 'requires_variant' => true, 'variant_table' => 'mau_sac_ngoi_hai_co_ct', 'variant_label' => 'Màu sắc'],
        'gach_hoa_thong_gio_ct' => ['pk' => 'gach_hoa_thong_gio_ct_id', 'label' => 'Gạch Hoa Thông Gió', 'route' => 'client.products.gach-hoa-thong-gio.detail', 'has_variants' => false, 'requires_variant' => false, 'variant_table' => null],
        'gach_trang_tri_ct' => ['pk' => 'gach_trang_tri_ct_id', 'label' => 'Gạch Trang Trí', 'route' => 'client.products.gach-trang-tri.detail', 'has_variants' => false, 'requires_variant' => false, 'variant_table' => null],
        'gach_co_bat_trang_ct' => ['pk' => 'gach_co_bat_trang_ct_id', 'label' => 'Gạch Cổ Bát Tràng', 'route' => 'client.products.gach-co-bat-trang.detail', 'has_variants' => false, 'requires_variant' => false, 'variant_table' => null, 'has_categories' => true, 'list_by_category' => true],
        'linh_vat_phong_thuy_ct' => ['pk' => 'linh_vat_phong_thuy_ct_id', 'label' => 'Linh Vật Phong Thủy', 'route' => 'client.products.linh-vat-phong-thuy.detail', 'has_variants' => false, 'requires_variant' => false, 'variant_table' => null],
        'lan_can_gom_su_ct' => ['pk' => 'lan_can_gom_su_ct_id', 'label' => 'Lan Can Gốm Sứ', 'route' => 'client.products.lan-can-gom-su.detail', 'has_variants' => true, 'requires_variant' => true, 'variant_table' => 'phan_loai_lan_can_gom_su_ct', 'price_source' => 'variant', 'price_format' => 'Giá: %s đ/m²', 'code_source' => 'variant', 'code_empty' => 'Đang cập nhật'],
        'den_vuon_gom_su_ct' => ['pk' => 'den_vuon_gom_su_ct_id', 'label' => 'Đèn Gốm Sứ', 'route' => 'client.products.den-gom-su.detail', 'has_variants' => true, 'requires_variant' => true, 'variant_table' => 'phan_loai_den_vuon_gom_su_ct', 'has_categories' => true, 'list_by_category' => true, 'price_source' => 'min', 'price_format' => 'Từ %s đ', 'code_source' => 'variant', 'code_falls_back_to_default' => true],
        'phu_kien_ngoi_ct' => ['pk' => 'phu_kien_ngoi_ct_id', 'label' => 'Phụ Kiện Ngói', 'route' => 'client.products.phu-kien-ngoi.ngoi-bo-noc.detail', 'has_variants' => true, 'requires_variant' => true, 'variant_table' => 'phan_loai_phu_kien_ngoi_ct', 'has_categories' => true, 'price_source' => 'variant', 'price_format' => 'Giá: %s đ/m²', 'price_empty' => 'Giá: Liên hệ', 'code_source' => 'variant', 'code_prefixes' => ['chu_van' => 'PKN-CV', 'default' => 'PKN-BN']],
    ];

    public static function all(): array
    {
        return array_map(static fn (array $config) => $config + self::DEFAULTS, self::TYPES);
    }

    /** @return array<string, mixed> rules of a type with no overrides */
    public static function defaults(): array
    {
        return self::DEFAULTS;
    }

    /** @return list<string> */
    public static function keys(): array
    {
        return array_keys(self::TYPES);
    }

    public static function get(string $type): ?array
    {
        return isset(self::TYPES[$type]) ? self::TYPES[$type] + self::DEFAULTS : null;
    }

    public static function fromRoute(?string $route): ?string
    {
        if ($route === null) {
            return null;
        }
        if (str_starts_with($route, 'client.products.phu-kien-ngoi.') && str_ends_with($route, '.detail')) {
            return 'phu_kien_ngoi_ct';
        }
        foreach (self::TYPES as $type => $config) {
            if ($config['route'] === $route) {
                return $type;
            }
        }

        return null;
    }

    public static function detailRoute(string $type, ?string $categoryType = null): string
    {
        if ($type === 'phu_kien_ngoi_ct' && $categoryType === 'chu_van') {
            return 'client.products.phu-kien-ngoi.bo-noc-chu-van.detail';
        }

        return self::TYPES[$type]['route'];
    }
}
