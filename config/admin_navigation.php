<?php

/*
|--------------------------------------------------------------------------
| Admin navigation
|--------------------------------------------------------------------------
|
| One module per topbar tab. The sidebar lists the items of the module that
| owns the current page, and the breadcrumb is read from the same tree, so a
| feature is regrouped by moving its entry here.
|
| Item shapes:
|   link    — label, route, active (route name patterns), optional icon, key,
|             params (query values that must match for the item to be active)
|   group   — label, children (links and headings), optional icon or dot
|   heading — heading
|
| `superadmin => true` hides a link from regular admins. Icon names are drawn
| by the `admin.layouts.nav-icon` component.
|
*/

return [
    [
        'key' => 'dashboard',
        'label' => 'Tổng quan',
        'icon' => 'dashboard',
        'route' => 'admin.dashboard',
        'items' => [
            ['label' => 'Tổng quan', 'icon' => 'dashboard', 'route' => 'admin.dashboard', 'active' => ['admin.dashboard']],
        ],
    ],

    [
        'key' => 'sales',
        'label' => 'Bán hàng',
        'icon' => 'cart',
        'route' => 'admin.orders.index',
        'items' => [
            ['key' => 'orders', 'label' => 'Đơn hàng', 'icon' => 'clipboard', 'route' => 'admin.orders.index', 'active' => ['admin.orders.*']],
            ['key' => 'consultation-requests', 'label' => 'Yêu cầu tư vấn', 'icon' => 'chat', 'route' => 'admin.consultation-requests.index', 'active' => ['admin.consultation-requests.*']],
            ['label' => 'Khách hàng', 'icon' => 'users', 'route' => 'admin.customers.index', 'active' => ['admin.customers.*']],
            ['label' => 'Mã giảm giá', 'icon' => 'ticket', 'route' => 'admin.coupons.index', 'active' => ['admin.coupons.*']],
        ],
    ],

    [
        'key' => 'products',
        'label' => 'Sản phẩm',
        'icon' => 'cube',
        'route' => 'admin.ngoi-am-duong-ct.index',
        'items' => [
            ['label' => 'Ngói Âm Dương', 'dot' => 'bg-red-800', 'children' => [
                ['label' => 'Sản phẩm chi tiết', 'route' => 'admin.ngoi-am-duong-ct.index', 'active' => ['admin.ngoi-am-duong-ct.*']],
                ['label' => 'Quản lý màu sắc', 'route' => 'admin.mau-sac-ngoi-am-duong-ct.index', 'active' => ['admin.mau-sac-ngoi-am-duong-ct.*']],
                ['label' => 'Định mức thi công', 'route' => 'admin.dinh-muc-ngoi-am-duong.index', 'active' => ['admin.dinh-muc-ngoi-am-duong.*']],
                ['label' => 'Cấu hình trang', 'route' => 'admin.ngoi-am-duong.index', 'active' => ['admin.ngoi-am-duong.*']],
            ]],
            ['label' => 'Ngói Hài', 'dot' => 'bg-red-700', 'children' => [
                ['heading' => 'Ngói Hài Văn Miếu'],
                ['label' => 'Danh sách SP & Màu', 'route' => 'admin.ngoi-hai-van-mieu-ct.index', 'active' => ['admin.ngoi-hai-van-mieu-ct.*', 'admin.mau-sac-ngoi-hai-van-mieu-ct.*']],
                ['label' => 'Định mức thi công', 'route' => 'admin.dinh-muc-ngoi-hai-van-mieu.index', 'active' => ['admin.dinh-muc-ngoi-hai-van-mieu.*']],
                ['heading' => 'Ngói Hài Cổ'],
                ['label' => 'Danh sách SP & Màu', 'route' => 'admin.ngoi-hai-co-ct.index', 'active' => ['admin.ngoi-hai-co-ct.*', 'admin.mau-sac-ngoi-hai-co-ct.*']],
                ['label' => 'Định mức thi công', 'route' => 'admin.dinh-muc-ngoi-hai-co.index', 'active' => ['admin.dinh-muc-ngoi-hai-co.*']],
                ['heading' => 'Trang danh mục'],
                ['label' => 'Cấu hình trang', 'route' => 'admin.ngoi-hai-van-mieu.index', 'active' => ['admin.ngoi-hai-van-mieu.*']],
            ]],
            ['label' => 'Gạch Hoa Thông Gió', 'dot' => 'bg-red-600', 'children' => [
                ['label' => 'Sản phẩm chi tiết', 'route' => 'admin.gach-hoa-thong-gio-ct.index', 'active' => ['admin.gach-hoa-thong-gio-ct.*']],
                ['label' => 'Định mức thi công', 'route' => 'admin.dinh-muc-gach-hoa-thong-gio.index', 'active' => ['admin.dinh-muc-gach-hoa-thong-gio.*']],
                ['label' => 'Cấu hình trang', 'route' => 'admin.gach-hoa-thong-gio.index', 'active' => ['admin.gach-hoa-thong-gio.*']],
            ]],
            ['label' => 'Gạch Trang Trí', 'dot' => 'bg-red-500', 'children' => [
                ['label' => 'Sản phẩm chi tiết', 'route' => 'admin.gach-trang-tri-ct.index', 'active' => ['admin.gach-trang-tri-ct.*']],
                ['label' => 'Định mức thi công', 'route' => 'admin.dinh-muc-gach-trang-tri.index', 'active' => ['admin.dinh-muc-gach-trang-tri.*']],
                ['label' => 'Cấu hình trang', 'route' => 'admin.gach-trang-tri.index', 'active' => ['admin.gach-trang-tri.*']],
            ]],
            ['label' => 'Gạch Cổ Bát Tràng', 'dot' => 'bg-orange-700', 'children' => [
                ['label' => 'Sản phẩm chi tiết', 'route' => 'admin.gach-co-bat-trang-ct.index', 'active' => ['admin.gach-co-bat-trang-ct.*']],
                ['label' => 'Định mức thi công', 'route' => 'admin.dinh-muc-gach-co-bat-trang.index', 'active' => ['admin.dinh-muc-gach-co-bat-trang.*']],
                ['label' => 'Cấu hình trang', 'route' => 'admin.gach-co-bat-trang.index', 'active' => ['admin.gach-co-bat-trang.*']],
            ]],
            ['label' => 'Phụ Kiện Ngói', 'dot' => 'bg-orange-500', 'children' => [
                ['heading' => 'Ngói Bò Nóc'],
                ['label' => 'Danh sách SP & Phân loại', 'route' => 'admin.phu-kien-ngoi-ct.index', 'params' => ['category_type' => 'bo_noc'], 'active' => ['admin.phu-kien-ngoi-ct.*', 'admin.phan-loai-phu-kien-ngoi-ct.*']],
                ['heading' => 'Bò Nóc Chữ Vạn'],
                ['label' => 'Danh sách SP & Phân loại', 'route' => 'admin.phu-kien-ngoi-ct.index', 'params' => ['category_type' => 'chu_van'], 'active' => ['admin.phu-kien-ngoi-ct.*', 'admin.phan-loai-phu-kien-ngoi-ct.*']],
                ['heading' => 'Trang danh mục'],
                ['label' => 'Cấu hình trang', 'route' => 'admin.phu-kien-ngoi.index', 'active' => ['admin.phu-kien-ngoi.*']],
            ]],
            ['label' => 'Lan Can Gốm Sứ', 'dot' => 'bg-yellow-500', 'children' => [
                ['label' => 'Sản phẩm & Phân loại', 'route' => 'admin.lan-can-gom-su-ct.index', 'active' => ['admin.lan-can-gom-su-ct.*', 'admin.phan-loai-lan-can-gom-su-ct.*']],
                ['label' => 'Cấu hình trang', 'route' => 'admin.lan-can-gom-xu.index', 'active' => ['admin.lan-can-gom-xu.*']],
            ]],
            ['label' => 'Đèn Vườn Gốm Sứ', 'dot' => 'bg-yellow-300', 'children' => [
                ['label' => 'Sản phẩm & Phân loại', 'route' => 'admin.den-vuon-gom-su-ct.index', 'active' => ['admin.den-vuon-gom-su-ct.*', 'admin.phan-loai-den-vuon-gom-su-ct.*']],
                ['label' => 'Cấu hình trang', 'route' => 'admin.den-gom-su.index', 'active' => ['admin.den-gom-su.*']],
            ]],
            ['label' => 'Linh Vật Phong Thủy', 'dot' => 'bg-cyan-600', 'children' => [
                ['label' => 'Sản phẩm chi tiết', 'route' => 'admin.linh-vat-phong-thuy-ct.index', 'active' => ['admin.linh-vat-phong-thuy-ct.*']],
                ['label' => 'Cấu hình trang', 'route' => 'admin.linh-vat-phong-thuy.index', 'active' => ['admin.linh-vat-phong-thuy.*']],
            ]],
        ],
    ],

    [
        'key' => 'content',
        'label' => 'Nội dung',
        'icon' => 'newspaper',
        'route' => 'admin.tin-tuc.index',
        'items' => [
            ['label' => 'Tin tức', 'icon' => 'newspaper', 'children' => [
                ['label' => 'Bài viết', 'route' => 'admin.tin-tuc.index', 'active' => ['admin.tin-tuc.*']],
                ['label' => 'Danh mục', 'route' => 'admin.danh-muc-tin-tuc.index', 'active' => ['admin.danh-muc-tin-tuc.*']],
            ]],
            ['label' => 'Dự án', 'icon' => 'folder', 'children' => [
                ['label' => 'Dự án', 'route' => 'admin.du-an.index', 'active' => ['admin.du-an.*']],
                ['label' => 'Danh mục', 'route' => 'admin.danh-muc-du-an.index', 'active' => ['admin.danh-muc-du-an.*']],
                ['label' => 'Cấu hình trang', 'route' => 'admin.trang-du-an.index', 'active' => ['admin.trang-du-an.*']],
            ]],
            ['label' => 'Hỏi đáp (FAQ)', 'icon' => 'question', 'route' => 'admin.pages.faq.edit', 'active' => ['admin.pages.faq.*', 'admin.pages.faqs.*']],
            ['heading' => 'Tài liệu hỗ trợ'],
            ['label' => 'Catalog', 'icon' => 'book', 'route' => 'admin.catalog.index', 'active' => ['admin.catalog.*']],
            ['label' => 'Video thi công', 'icon' => 'video', 'route' => 'admin.thi-cong.index', 'active' => ['admin.thi-cong.*']],
        ],
    ],

    [
        'key' => 'appearance',
        'label' => 'Giao diện',
        'icon' => 'template',
        'route' => 'admin.trang_chu.edit',
        'items' => [
            ['heading' => 'Trang đơn'],
            ['label' => 'Trang chủ', 'icon' => 'home', 'route' => 'admin.trang_chu.edit', 'active' => ['admin.trang_chu.*']],
            ['label' => 'Về chúng tôi', 'icon' => 'info', 'route' => 'admin.pages.ve_chung_toi.edit', 'active' => ['admin.pages.ve_chung_toi.*']],
            ['label' => 'Xưởng sản xuất', 'icon' => 'building', 'route' => 'admin.pages.factory.edit', 'active' => ['admin.pages.factory.*']],
            ['label' => 'Liên hệ', 'icon' => 'mail', 'route' => 'admin.pages.contact.edit', 'active' => ['admin.pages.contact.*']],
            ['heading' => 'Khối dùng chung'],
            ['label' => 'Giá trị vượt trội', 'icon' => 'sparkles', 'route' => 'admin.gia-tri-vuot-troi.index', 'active' => ['admin.gia-tri-vuot-troi.*']],
            ['label' => 'Giải thưởng & Thành tựu', 'icon' => 'badge', 'route' => 'admin.giai-thuong-thanh-tuu.index', 'active' => ['admin.giai-thuong-thanh-tuu.*']],
        ],
    ],

    [
        'key' => 'system',
        'label' => 'Hệ thống',
        'icon' => 'cog',
        'route' => 'admin.settings.edit',
        'items' => [
            ['label' => 'Cài đặt website', 'icon' => 'cog', 'route' => 'admin.settings.edit', 'active' => ['admin.settings.*']],
            ['label' => 'Quản trị viên', 'icon' => 'user-shield', 'route' => 'admin.users.index', 'active' => ['admin.users.*'], 'superadmin' => true],
            ['label' => 'Sao lưu nội dung', 'icon' => 'archive', 'route' => 'admin.content-archive.index', 'active' => ['admin.content-archive.*'], 'superadmin' => true],
        ],
    ],
];
