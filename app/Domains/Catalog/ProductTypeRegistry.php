<?php

namespace App\Domains\Catalog;

final class ProductTypeRegistry
{
    /** @var array<string, array{pk: string, label: string, route: string, has_variants: bool, requires_variant: bool, variant_table: ?string}> */
    private const TYPES = [
        'ngoi_am_duong_ct' => ['pk' => 'ngoi_am_duong_ct_id', 'label' => 'Ngói Âm Dương', 'route' => 'client.products.ngoi-am-duong.detail', 'has_variants' => false, 'requires_variant' => false, 'variant_table' => null],
        'ngoi_hai_van_mieu_ct' => ['pk' => 'ngoi_hai_van_mieu_ct_id', 'label' => 'Ngói Hài Văn Miếu', 'route' => 'client.products.ngoi-hai-van-mieu.detail', 'has_variants' => true, 'requires_variant' => false, 'variant_table' => 'mau_sac_ngoi_hai_van_mieu_ct'],
        'ngoi_hai_co_ct' => ['pk' => 'ngoi_hai_co_ct_id', 'label' => 'Ngói Hài Cổ', 'route' => 'client.products.ngoi-hai-co.detail', 'has_variants' => true, 'requires_variant' => true, 'variant_table' => 'mau_sac_ngoi_hai_co_ct'],
        'gach_hoa_thong_gio_ct' => ['pk' => 'gach_hoa_thong_gio_ct_id', 'label' => 'Gạch Hoa Thông Gió', 'route' => 'client.products.gach-hoa-thong-gio.detail', 'has_variants' => false, 'requires_variant' => false, 'variant_table' => null],
        'gach_trang_tri_ct' => ['pk' => 'gach_trang_tri_ct_id', 'label' => 'Gạch Trang Trí', 'route' => 'client.products.gach-trang-tri.detail', 'has_variants' => false, 'requires_variant' => false, 'variant_table' => null],
        'gach_co_bat_trang_ct' => ['pk' => 'gach_co_bat_trang_ct_id', 'label' => 'Gạch Cổ Bát Tràng', 'route' => 'client.products.gach-co-bat-trang.detail', 'has_variants' => false, 'requires_variant' => false, 'variant_table' => null],
        'linh_vat_phong_thuy_ct' => ['pk' => 'linh_vat_phong_thuy_ct_id', 'label' => 'Linh Vật Phong Thủy', 'route' => 'client.products.linh-vat-phong-thuy.detail', 'has_variants' => false, 'requires_variant' => false, 'variant_table' => null],
        'lan_can_gom_su_ct' => ['pk' => 'lan_can_gom_su_ct_id', 'label' => 'Lan Can Gốm Sứ', 'route' => 'client.products.lan-can-gom-su.detail', 'has_variants' => true, 'requires_variant' => true, 'variant_table' => 'phan_loai_lan_can_gom_su_ct'],
        'den_vuon_gom_su_ct' => ['pk' => 'den_vuon_gom_su_ct_id', 'label' => 'Đèn Gốm Sứ', 'route' => 'client.products.den-gom-su.detail', 'has_variants' => true, 'requires_variant' => true, 'variant_table' => 'phan_loai_den_vuon_gom_su_ct'],
        'phu_kien_ngoi_ct' => ['pk' => 'phu_kien_ngoi_ct_id', 'label' => 'Phụ Kiện Ngói', 'route' => 'client.products.phu-kien-ngoi.ngoi-bo-noc.detail', 'has_variants' => true, 'requires_variant' => true, 'variant_table' => 'phan_loai_phu_kien_ngoi_ct'],
    ];

    public static function all(): array
    {
        return self::TYPES;
    }

    public static function get(string $type): ?array
    {
        return self::TYPES[$type] ?? null;
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
