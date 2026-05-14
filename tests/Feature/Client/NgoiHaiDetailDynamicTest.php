<?php

use App\Models\DinhMucNgoiHaiCo;
use App\Models\DinhMucNgoiHaiVanMieu;
use App\Models\MauSacNgoiHaiCoCt;
use App\Models\MauSacNgoiHaiVanMieuCt;
use App\Models\NgoiHaiCoCt;
use App\Models\NgoiHaiVanMieu;
use App\Models\NgoiHaiVanMieuCt;
use Illuminate\Support\Facades\Blade;

beforeEach(function () {
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
});

function createNgoiHaiVanMieuProduct(array $overrides = []): NgoiHaiVanMieuCt
{
    return NgoiHaiVanMieuCt::query()->create(array_merge([
        'name' => 'Ngói Hài Dynamic',
        'color' => 'Tự chọn',
        'images' => ['ngoi-hai/product-main.jpg'],
        'price' => 0,
        'des' => ['Mô tả động 1', 'Mô tả động 2'],
        'mau_sac_id' => 0,
        'size' => 'L280 x W280 x H54mm',
        'size_image' => 'ngoi-hai/size-image.jpg',
        'is_delete' => 0,
    ], $overrides));
}

test('ngoi hai van mieu detail renders dynamic banner gallery variants calculator fabrication and compare table', function () {
    $product = createNgoiHaiVanMieuProduct();

    MauSacNgoiHaiVanMieuCt::query()->create([
        'name' => 'Men xanh',
        'image' => 'ngoi-hai/variant-blue.jpg',
        'code' => 'NHVM-BLUE',
        'price' => 675000,
        'ngoi_hai_van_mieu_ct_id' => $product->ngoi_hai_van_mieu_ct_id,
        'is_delete' => 0,
    ]);

    MauSacNgoiHaiVanMieuCt::query()->create([
        'name' => 'Men đã xóa',
        'image' => 'ngoi-hai/deleted.jpg',
        'code' => 'NHVM-DELETED',
        'price' => 1,
        'ngoi_hai_van_mieu_ct_id' => $product->ngoi_hai_van_mieu_ct_id,
        'is_delete' => 1,
    ]);

    $related = createNgoiHaiVanMieuProduct([
        'name' => 'Ngói Hài Liên Quan',
        'color' => 'Nâu đỏ',
        'price' => 500000,
        'size' => 'L200 x W200',
    ]);

    DinhMucNgoiHaiVanMieu::query()->create([
        'roof_type' => 'default',
        'ngoi_tren_mai_go' => 125,
        'ngoi_tren_mai_be_tong' => 75,
    ]);

    $this->get(route('client.products.ngoi-hai-van-mieu.detail', $product->ngoi_hai_van_mieu_ct_id))
        ->assertOk()
        ->assertSee('storage/ngoi-hai/banner-detail.jpg', false)
        ->assertSee('storage/ngoi-hai/product-main.jpg', false)
        ->assertSee('NHVM-BLUE')
        ->assertSee('675.000 đ/m²')
        ->assertSee('Mô tả động 1')
        ->assertSee('storage/ngoi-hai/size-image.jpg', false)
        ->assertSee('storage/ngoi-hai/cong-doan-01.jpg', false)
        ->assertSee('data-compare-table="true"', false)
        ->assertSee('Ngói Hài Liên Quan')
        ->assertSee('Nâu đỏ')
        ->assertDontSee('Men đã xóa');
});

test('ngoi hai co detail uses hai co identifiers and filters deleted variants', function () {
    $product = NgoiHaiCoCt::query()->create([
        'name' => 'Ngói Hài Cổ Dynamic',
        'color' => 'Tự chọn',
        'images' => ['ngoi-hai-co/product.jpg'],
        'des' => ['Mô tả Hài Cổ'],
        'size' => 'L180 x W180',
        'size_image' => 'ngoi-hai-co/size.jpg',
        'is_delete' => 0,
    ]);

    MauSacNgoiHaiCoCt::query()->create([
        'name' => 'Men cổ',
        'image' => 'ngoi-hai-co/variant.jpg',
        'code' => 'NHC-ACTIVE',
        'price' => 333000,
        'ngoi_hai_co_ct_id' => $product->ngoi_hai_co_ct_id,
        'is_delete' => 0,
    ]);

    MauSacNgoiHaiCoCt::query()->create([
        'name' => 'Men cổ đã xóa',
        'image' => 'ngoi-hai-co/deleted.jpg',
        'code' => 'NHC-DELETED',
        'price' => 1,
        'ngoi_hai_co_ct_id' => $product->ngoi_hai_co_ct_id,
        'is_delete' => 1,
    ]);

    DinhMucNgoiHaiCo::query()->create([
        'roof_type' => 'default',
        'ngoi_tren_mai_go' => 100,
        'ngoi_tren_mai_be_tong' => 60,
    ]);

    $this->get(route('client.products.ngoi-hai-co.detail', $product->ngoi_hai_co_ct_id))
        ->assertOk()
        ->assertSee('Ngói Hài Cổ')
        ->assertSee('value="ngoi_hai_co_ct"', false)
        ->assertSee('value="'.$product->ngoi_hai_co_ct_id.'"', false)
        ->assertSee('data-variant-id="', false)
        ->assertSee('NHC-ACTIVE')
        ->assertSee('333.000 đ/m²')
        ->assertDontSee('NHC-DELETED')
        ->assertDontSee('Men cổ đã xóa');
});

test('recommendations compare mode exposes explicit compare table marker', function () {
    $product = createNgoiHaiVanMieuProduct([
        'name' => 'Ngói compare mode',
        'price' => 123000,
        'color' => '',
        'size' => 'L100',
    ]);

    $html = Blade::render(
        '<x-products.recommendations :related-products="$products" route-name="client.products.ngoi-hai-van-mieu.detail" pk-field="ngoi_hai_van_mieu_ct_id" product-type="ngoi_hai_van_mieu_ct" :compare-table="true" />',
        ['products' => collect([$product])]
    );

    expect($html)->toContain('data-compare-table="true"')
        ->and($html)->toContain('Giá')
        ->and($html)->toContain('Màu sắc')
        ->and($html)->toContain('Kích thước')
        ->and($html)->toContain('Tự chọn');
});
