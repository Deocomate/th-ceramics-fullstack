<?php

use App\Models\DanhMucDuAn;
use App\Models\DuAn;
use App\Models\GachTrangTri;

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
