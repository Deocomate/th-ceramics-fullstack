<?php

return [
    // Ordered so parents are restored before their children. Business and auth data
    // must never be added to this list.
    'tables' => [
        'ngoi_am_duong', 'ngoi_am_duong_ct', 'mau_sac_ngoi_am_duong_ct', 'dinh_muc_ngoi_am_duong',
        'ngoi_hai_van_mieu', 'ngoi_hai_co_ct', 'mau_sac_ngoi_hai_co_ct', 'dinh_muc_ngoi_hai_co',
        'ngoi_hai_van_mieu_ct', 'mau_sac_ngoi_hai_van_mieu_ct', 'dinh_muc_ngoi_hai_van_mieu',
        'gach_hoa_thong_gio', 'gach_hoa_thong_gio_anh', 'gia_tri_gach_hoa_thong_gio',
        'gach_hoa_thong_gio_ct', 'dinh_muc_gach_hoa_thong_gio',
        'gach_trang_tri', 'dau_an_gach_trang_tri', 'gach_trang_tri_ct', 'dinh_muc_gach_trang_tri',
        'gach_co_bat_trang', 'gach_co_bat_trang_anh', 'gach_co_bat_trang_ct', 'dinh_muc_gach_co_bat_trang',
        'linh_vat_phong_thuy', 'linh_vat', 'linh_vat_phong_thuy_anh', 'linh_vat_phong_thuy_ct',
        'phu_kien_ngoi', 'phu_kien_ngoi_ct', 'phan_loai_phu_kien_ngoi_ct',
        'lan_can_gom_xu', 'lan_can_gom_su_ct', 'phan_loai_lan_can_gom_su_ct',
        'den_gom_su', 'den_gom_su_anh', 'den_vuon_gom_su_ct', 'phan_loai_den_vuon_gom_su_ct',
        'danh_muc_du_an', 'du_an', 'danh_muc_tin_tuc', 'tin_tuc', 'thi_cong', 'catalog',
        'trang_chu', 've_chung_toi', 'giai_thuong_thanh_tuu', 'gia_tri_vuot_troi',
        'page_factory', 'page_contact', 'page_faq', 'faqs', 'trang_du_an',
    ],
    'excluded_tables' => [
        'users', 'password_reset_tokens', 'sessions', 'cache', 'cache_locks',
        'jobs', 'job_batches', 'failed_jobs', 'orders', 'order_items',
        'coupons', 'consultation_requests', 'migrations',
    ],
    'max_uncompressed_bytes' => 2_000_000_000,
];
