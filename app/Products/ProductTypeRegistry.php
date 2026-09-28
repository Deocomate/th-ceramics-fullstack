<?php

namespace App\Products;

use App\Models\DenVuonGomSuCt;
use App\Models\GachCoBatTrangCt;
use App\Models\GachHoaThongGioCt;
use App\Models\GachTrangTriCt;
use App\Models\LanCanGomSuCt;
use App\Models\LinhVatPhongThuyCt;
use App\Models\MauSacNgoiHaiCoCt;
use App\Models\MauSacNgoiHaiVanMieuCt;
use App\Models\NgoiAmDuongCt;
use App\Models\NgoiHaiCoCt;
use App\Models\NgoiHaiVanMieuCt;
use App\Models\PhanLoaiDenVuonGomSuCt;
use App\Models\PhanLoaiLanCanGomSuCt;
use App\Models\PhanLoaiPhuKienNgoiCt;
use App\Models\PhuKienNgoiCt;

final class ProductTypeRegistry
{
    /** @var array<string, array{pk: string, label: string, route: string, model: class-string, variant_model: ?class-string, variant_table: ?string, variant_pk: ?string, variant_fk: ?string, relation: ?string, requires_variant: bool}> */
    private const TYPES = [
        'ngoi_am_duong_ct' => ['pk' => 'ngoi_am_duong_ct_id', 'label' => 'Ngói Âm Dương', 'route' => 'client.products.ngoi-am-duong.detail', 'model' => NgoiAmDuongCt::class, 'variant_model' => null, 'variant_table' => null, 'variant_pk' => null, 'variant_fk' => null, 'relation' => null, 'requires_variant' => false],
        'ngoi_hai_van_mieu_ct' => ['pk' => 'ngoi_hai_van_mieu_ct_id', 'label' => 'Ngói Hài Văn Miếu', 'route' => 'client.products.ngoi-hai-van-mieu.detail', 'model' => NgoiHaiVanMieuCt::class, 'variant_model' => MauSacNgoiHaiVanMieuCt::class, 'variant_table' => 'mau_sac_ngoi_hai_van_mieu_ct', 'variant_pk' => 'mau_sac_ngoi_hai_van_mieu_ct_id', 'variant_fk' => 'ngoi_hai_van_mieu_ct_id', 'relation' => 'mauSacs', 'requires_variant' => false],
        'ngoi_hai_co_ct' => ['pk' => 'ngoi_hai_co_ct_id', 'label' => 'Ngói Hài Cổ', 'route' => 'client.products.ngoi-hai-co.detail', 'model' => NgoiHaiCoCt::class, 'variant_model' => MauSacNgoiHaiCoCt::class, 'variant_table' => 'mau_sac_ngoi_hai_co_ct', 'variant_pk' => 'mau_sac_ngoi_hai_co_ct_id', 'variant_fk' => 'ngoi_hai_co_ct_id', 'relation' => 'mauSacs', 'requires_variant' => true],
        'gach_hoa_thong_gio_ct' => ['pk' => 'gach_hoa_thong_gio_ct_id', 'label' => 'Gạch Hoa Thông Gió', 'route' => 'client.products.gach-hoa-thong-gio.detail', 'model' => GachHoaThongGioCt::class, 'variant_model' => null, 'variant_table' => null, 'variant_pk' => null, 'variant_fk' => null, 'relation' => null, 'requires_variant' => false],
        'gach_trang_tri_ct' => ['pk' => 'gach_trang_tri_ct_id', 'label' => 'Gạch Trang Trí', 'route' => 'client.products.gach-trang-tri.detail', 'model' => GachTrangTriCt::class, 'variant_model' => null, 'variant_table' => null, 'variant_pk' => null, 'variant_fk' => null, 'relation' => null, 'requires_variant' => false],
        'gach_co_bat_trang_ct' => ['pk' => 'gach_co_bat_trang_ct_id', 'label' => 'Gạch Cổ Bát Tràng', 'route' => 'client.products.gach-co-bat-trang.detail', 'model' => GachCoBatTrangCt::class, 'variant_model' => null, 'variant_table' => null, 'variant_pk' => null, 'variant_fk' => null, 'relation' => null, 'requires_variant' => false],
        'linh_vat_phong_thuy_ct' => ['pk' => 'linh_vat_phong_thuy_ct_id', 'label' => 'Linh Vật Phong Thủy', 'route' => 'client.products.linh-vat-phong-thuy.detail', 'model' => LinhVatPhongThuyCt::class, 'variant_model' => null, 'variant_table' => null, 'variant_pk' => null, 'variant_fk' => null, 'relation' => null, 'requires_variant' => false],
        'lan_can_gom_su_ct' => ['pk' => 'lan_can_gom_su_ct_id', 'label' => 'Lan Can Gốm Sứ', 'route' => 'client.products.lan-can-gom-su.detail', 'model' => LanCanGomSuCt::class, 'variant_model' => PhanLoaiLanCanGomSuCt::class, 'variant_table' => 'phan_loai_lan_can_gom_su_ct', 'variant_pk' => 'phan_loai_lan_can_gom_su_ct_id', 'variant_fk' => 'lan_can_gom_su_ct_id', 'relation' => 'phanLoais', 'requires_variant' => true],
        'den_vuon_gom_su_ct' => ['pk' => 'den_vuon_gom_su_ct_id', 'label' => 'Đèn Gốm Sứ', 'route' => 'client.products.den-gom-su.detail', 'model' => DenVuonGomSuCt::class, 'variant_model' => PhanLoaiDenVuonGomSuCt::class, 'variant_table' => 'phan_loai_den_vuon_gom_su_ct', 'variant_pk' => 'phan_loai_den_vuon_gom_su_ct_id', 'variant_fk' => 'den_vuon_gom_su_ct_id', 'relation' => 'phanLoais', 'requires_variant' => true],
        'phu_kien_ngoi_ct' => ['pk' => 'phu_kien_ngoi_ct_id', 'label' => 'Phụ Kiện Ngói', 'route' => 'client.products.phu-kien-ngoi.ngoi-bo-noc.detail', 'model' => PhuKienNgoiCt::class, 'variant_model' => PhanLoaiPhuKienNgoiCt::class, 'variant_table' => 'phan_loai_phu_kien_ngoi_ct', 'variant_pk' => 'phan_loai_phu_kien_ngoi_ct_id', 'variant_fk' => 'phu_kien_ngoi_ct_id', 'relation' => 'phanLoais', 'requires_variant' => true],
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
