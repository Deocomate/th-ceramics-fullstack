<?php

use App\Support\ProductPrice;

test('maps product categories to their selling units', function () {
    expect(ProductPrice::unitForType('ngoi_am_duong_ct'))->toBe('m²')
        ->and(ProductPrice::unitForType('ngoi_hai_van_mieu_ct'))->toBe('m²')
        ->and(ProductPrice::unitForType('gach_co_bat_trang_ct'))->toBe('viên')
        ->and(ProductPrice::unitForType('gach_hoa_thong_gio_ct'))->toBe('viên')
        ->and(ProductPrice::unitForType('gach_trang_tri_ct'))->toBe('viên')
        ->and(ProductPrice::unitForType('phu_kien_ngoi_ct'))->toBe('chiếc')
        ->and(ProductPrice::unitForType('den_vuon_gom_su_ct'))->toBe('chiếc')
        ->and(ProductPrice::unitForType('lan_can_gom_su_ct'))->toBe('chiếc');
});

test('normalizes existing card prices while preserving labels and contact text', function () {
    expect(ProductPrice::withUnit('Giá: 120.000 đ/m²', 'phu_kien_ngoi_ct'))
        ->toBe('Giá: 120.000 đ/chiếc')
        ->and(ProductPrice::withUnit('Từ 150.000đ', 'den_vuon_gom_su_ct'))
        ->toBe('Từ 150.000 đ/chiếc')
        ->and(ProductPrice::withUnit('120.000 đ/m2', 'gach_trang_tri_ct'))
        ->toBe('120.000 đ/viên')
        ->and(ProductPrice::withUnit('Liên hệ', 'ngoi_am_duong_ct'))
        ->toBe('Liên hệ')
        ->and(ProductPrice::formatAmount(120000, 'ngoi_hai_van_mieu_ct'))
        ->toBe('120.000 đ/m²');
});
