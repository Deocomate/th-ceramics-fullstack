<?php

use App\Models\DinhMucNgoiAmDuong;
use App\Models\DinhMucNgoiHaiVanMieu;
use App\Models\NgoiAmDuongCt;
use App\Models\NgoiHaiVanMieu;
use App\Models\NgoiHaiVanMieuCt;
use Illuminate\Support\Facades\Blade;

test('shape area block partial renders standardized shape calculator markup', function () {
    $html = Blade::render('<x-client.shared.shape-area-block :index="1" variant="weight" />');

    expect($html)
        ->toContain('data-area-block')
        ->toContain('data-shape-select')
        ->toContain('data-input-wrapper')
        ->toContain('data-input-role="primary"')
        ->toContain('data-input-role="secondary"')
        ->toContain('data-input-role="height"')
        ->toContain('data-dimension="primary"')
        ->toContain('data-dimension="secondary"')
        ->toContain('data-dimension="height"')
        ->toContain('value="rectangle"')
        ->toContain('value="trapezoid"')
        ->toContain('value="triangle"');
});

test('quantity calculator partial keeps rectangle-only multi-area markup', function () {
    $html = Blade::render('<x-client.shared.quantity-calculator />');

    expect($html)
        ->toContain('data-quantity-calculator')
        ->toContain('data-area-block')
        ->toContain('data-input-length')
        ->toContain('data-input-width')
        ->toContain('data-remove-area')
        ->toContain('btn-add-area');
});

test('ngoi am duong detail renders weight calculator with shape area markup', function () {
    $product = NgoiAmDuongCt::query()->create([
        'code' => 'NAD-CALC-MARKUP',
        'name' => 'Ngói Âm Dương Calculator Markup',
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
        'ngoi_am' => 25,
        'ngoi_duong' => 15,
        'diem' => 3,
    ]);

    $this->get(route('client.products.ngoi-am-duong.detail', $product->ngoi_am_duong_ct_id))
        ->assertOk()
        ->assertSee('data-weight-calculator', false)
        ->assertSee('data-weight-calculator-areas', false)
        ->assertSee('data-shape-select', false)
        ->assertSee('data-dimension="primary"', false)
        ->assertSee('data-dimension="secondary"', false)
        ->assertSee('data-dimension="height"', false)
        ->assertSee('data-add-area', false);
});

test('ngoi hai van mieu detail renders shape calculator blocks', function () {
    NgoiHaiVanMieu::query()->create([
        'thumbnail_main' => 'ngoi-hai/banner-detail.jpg',
        'title1' => 'Tiêu đề 1',
        'thumbnail1' => 'ngoi-hai/thumb-1.jpg',
        'title2' => 'Tiêu đề 2',
        'thumbnail2' => 'ngoi-hai/thumb-2.jpg',
        'title3' => 'Tiêu đề 3',
        'thumbnail3' => 'ngoi-hai/thumb-3.jpg',
        'video' => null,
        'images' => ['ngoi-hai/cong-doan-01.jpg'],
    ]);

    $product = NgoiHaiVanMieuCt::query()->create([
        'name' => 'Ngói Hài Calculator Markup',
        'color' => 'Tự chọn',
        'images' => ['ngoi-hai/product-main.jpg'],
        'price' => 0,
        'des' => ['Mô tả test'],
        'mau_sac_id' => 0,
        'size' => 'L280 x W280 x H54mm',
        'size_image' => 'ngoi-hai/size-image.jpg',
        'is_delete' => 0,
    ]);

    DinhMucNgoiHaiVanMieu::query()->create([
        'roof_type' => 'default',
        'ngoi_tren_mai_go' => 125,
        'ngoi_tren_mai_be_tong' => 75,
    ]);

    $response = $this->get(route('client.products.ngoi-hai-van-mieu.detail', $product->ngoi_hai_van_mieu_ct_id));

    $response->assertOk()
        ->assertSee('data-hai-vm-calculator', false)
        ->assertSee('data-shape-select', false)
        ->assertSee('data-dimension="primary"', false)
        ->assertSee('data-dimension="height"', false)
        ->assertSee('data-add-area', false);

    expect(substr_count($response->getContent(), 'data-area-block'))->toBeGreaterThanOrEqual(2);
});
