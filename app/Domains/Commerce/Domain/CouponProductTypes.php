<?php

namespace App\Domains\Commerce\Domain;

final class CouponProductTypes
{
    public static function all(): array
    {
        return [
            'ngoi_am_duong_ct' => 'Ngói Âm Dương',
            'ngoi_hai_van_mieu_ct' => 'Ngói Hài Văn Miếu',
            'ngoi_hai_co_ct' => 'Ngói Hài Cổ',
            'gach_hoa_thong_gio_ct' => 'Gạch Hoa Thông Gió',
            'gach_trang_tri_ct' => 'Gạch Trang Trí',
            'gach_co_bat_trang_ct' => 'Gạch Cổ Bát Tràng',
            'linh_vat_phong_thuy_ct' => 'Linh Vật Phong Thủy',
            'lan_can_gom_xu' => 'Lan Can Gốm Sứ',
            'den_gom_su' => 'Đèn Gốm Sứ',
            'den_vuon_gom_su_ct' => 'Đèn Gốm Sứ Chi Tiết',
            'phu_kien_ngoi_ct' => 'Phụ Kiện Ngói',
        ];
    }
}
