<?php

use App\Models\MauSacNgoiHaiVanMieuCt;
use App\Models\NgoiAmDuongCt;
use App\Models\NgoiHaiVanMieuCt;

test('quick search returns direct product matches by name or code', function () {
    $product = NgoiAmDuongCt::query()->create([
        'code' => 'NAD-SEARCH-001',
        'name' => 'Ngói âm dương tìm nhanh',
        'images' => ['assets/images/ngoi-01.jpg'],
        'price' => 862000,
        'des' => ['Mô tả'],
        'is_delete' => false,
    ]);

    $this->getJson(route('client.search.quick', ['q' => 'NAD-SEARCH']))
        ->assertSuccessful()
        ->assertJsonPath('products.0.id', $product->ngoi_am_duong_ct_id)
        ->assertJsonPath('products.0.name', 'Ngói âm dương tìm nhanh')
        ->assertJsonPath('products.0.code', 'NAD-SEARCH-001')
        ->assertJsonPath('products.0.category', 'Ngói Âm Dương')
        ->assertJsonPath('products.0.price', 862000)
        ->assertJsonPath('products.0.price_formatted', '862.000đ')
        ->assertJsonPath('products.0.url', route('client.products.ngoi-am-duong.detail', $product->ngoi_am_duong_ct_id));
});

test('quick search returns variant backed product matches by code', function () {
    $product = NgoiHaiVanMieuCt::query()->create([
        'name' => 'Ngói hài văn miếu đại',
        'images' => ['assets/images/ngoi-hai-01.png'],
        'price' => 100000,
        'des' => ['Mô tả'],
        'mau_sac_id' => 1,
        'is_delete' => false,
    ]);

    MauSacNgoiHaiVanMieuCt::query()->create([
        'name' => 'Men đỏ',
        'image' => 'assets/images/ngoi-hai-02.png',
        'code' => 'NHVM-RED-001',
        'price' => 120000,
        'ngoi_hai_van_mieu_ct_id' => $product->ngoi_hai_van_mieu_ct_id,
        'is_delete' => false,
    ]);

    $this->getJson(route('client.search.quick', ['q' => 'NHVM-RED']))
        ->assertSuccessful()
        ->assertJsonPath('products.0.id', $product->ngoi_hai_van_mieu_ct_id)
        ->assertJsonPath('products.0.name', 'Ngói hài văn miếu đại - Men đỏ')
        ->assertJsonPath('products.0.code', 'NHVM-RED-001')
        ->assertJsonPath('products.0.category', 'Ngói Hài Văn Miếu')
        ->assertJsonPath('products.0.price', 120000)
        ->assertJsonPath('products.0.url', route('client.products.ngoi-hai-van-mieu.detail', $product->ngoi_hai_van_mieu_ct_id));
});

test('quick search excludes deleted products', function () {
    NgoiAmDuongCt::query()->create([
        'code' => 'NAD-DELETED-001',
        'name' => 'Ngói đã xóa',
        'images' => ['assets/images/ngoi-01.jpg'],
        'price' => 862000,
        'des' => ['Mô tả'],
        'is_delete' => true,
    ]);

    $this->getJson(route('client.search.quick', ['q' => 'NAD-DELETED']))
        ->assertSuccessful()
        ->assertJsonPath('products', []);
});

test('quick search ignores empty and one character queries', function (?string $keyword) {
    NgoiAmDuongCt::query()->create([
        'code' => 'NAD-SHORT-001',
        'name' => 'Ngói short query',
        'images' => ['assets/images/ngoi-01.jpg'],
        'price' => 862000,
        'des' => ['Mô tả'],
        'is_delete' => false,
    ]);

    $this->getJson(route('client.search.quick', ['q' => $keyword]))
        ->assertSuccessful()
        ->assertJsonPath('products', []);
})->with([
    'empty' => '',
    'one character' => 'N',
    'missing' => null,
]);
