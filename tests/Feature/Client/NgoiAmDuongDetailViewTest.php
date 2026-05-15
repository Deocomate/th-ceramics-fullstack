<?php

use App\Models\DinhMucNgoiAmDuong;
use App\Models\NgoiAmDuongCt;

test('ngoi am duong detail renders local calculator applications and installation partials', function () {
    $product = NgoiAmDuongCt::query()->create([
        'code' => 'NAD-PARTIALS-001',
        'name' => 'Ngói Âm Dương Partials',
        'images' => ['assets/images/ngoi-01.jpg'],
        'price' => 862000,
        'des' => ['Mô tả test'],
        'size' => 'L200 x W200',
        'size_image' => 'assets/images/ngoi-am-duong-size.png',
        'is_delete' => false,
    ]);

    DinhMucNgoiAmDuong::query()->create([
        'roof_type' => 'Mái gỗ',
        'tile_type' => '15 cặp/m²',
        'ngoi_am' => 15,
        'ngoi_duong' => 15,
        'diem' => 2,
    ]);

    $this->get(route('client.products.ngoi-am-duong.detail', $product->ngoi_am_duong_ct_id))
        ->assertOk()
        ->assertSee('data-weight-calculator', false)
        ->assertSee('ỨNG DỤNG ĐA DẠNG')
        ->assertSee('HƯỚNG DẪN LẮP ĐẶT');
});
