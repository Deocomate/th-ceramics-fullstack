<?php

use App\Models\DanhMucDuAn;
use App\Models\DuAn;
use App\Models\GachTrangTri;
use App\Models\GachTrangTriCt;

test('gach trang tri page renders dynamic applications and global projects', function () {
    $category = DanhMucDuAn::query()->create([
        'ten_danh_muc' => 'Gạch Trang Trí',
        'is_delete' => 0,
    ]);

    DuAn::query()->create([
        'ten_du_an' => 'Nhà hàng gốm Việt',
        'dia_diem' => 'Hà Nội',
        'san_pham' => 'Gạch trang trí men rạn',
        'nam' => 2026,
        'images' => ['assets/images/trang-tri-slide-01.jpg'],
        'danh_muc_du_an_id' => $category->danh_muc_du_an_id,
        'slug' => 'nha-hang-gom-viet',
    ]);

    GachTrangTri::query()->create([
        'thumbnail_main' => 'assets/images/gach-trang-tri-banner.png',
        'video' => null,
        'images' => ['assets/images/trang-tri-01.png', 'assets/images/trang-tri-02.png'],
        'ung_dung_da_dang' => [
            'main' => ['title' => 'Tường trang trí', 'image' => 'assets/images/trang-tri-01.png'],
            'sub_1' => ['title' => null, 'image' => null],
            'sub_2' => ['title' => null, 'image' => null],
            'sub_3' => ['title' => null, 'image' => null],
            'sub_4' => ['title' => null, 'image' => null],
        ],
    ]);

    $this->get(route('client.products.gach-trang-tri.index'))
        ->assertOk()
        ->assertSee('Ứng dụng đa dạng')
        ->assertSee('Tường trang trí')
        ->assertSee('Nhà hàng gốm Việt')
        ->assertSee('Hà Nội')
        ->assertSee('Gạch trang trí men rạn')
        ->assertDontSee('Dấu Ấn Gạch Trang Trí');
});

test('gach trang tri product listing uses custom pagination and eight products per page', function () {
    GachTrangTri::query()->create([
        'thumbnail_main' => 'assets/images/gach-trang-tri-banner.png',
        'video' => null,
        'images' => [],
        'ung_dung_da_dang' => [],
    ]);

    for ($i = 1; $i <= 9; $i++) {
        GachTrangTriCt::query()->create([
            'code' => 'GTT-PAGED-'.str_pad((string) $i, 3, '0', STR_PAD_LEFT),
            'name' => 'Sản phẩm giới hạn '.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
            'images' => ['assets/images/trang-tri-01.png'],
            'price' => 100000 + $i,
            'des' => [],
            'size' => '10 x 20 cm',
            'is_delete' => 0,
        ]);
    }

    $this->get(route('client.products.gach-trang-tri.index', ['sort' => 'name_asc']))
        ->assertOk()
        ->assertSee('aria-label="Pagination"', false)
        ->assertSee('sort=name_asc', false)
        ->assertSee('page=2', false)
        ->assertSee('Sản phẩm giới hạn 01')
        ->assertSee('Sản phẩm giới hạn 08')
        ->assertDontSee('Sản phẩm giới hạn 09');
});
