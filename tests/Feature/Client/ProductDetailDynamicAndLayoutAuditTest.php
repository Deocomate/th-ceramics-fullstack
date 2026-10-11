<?php

use App\Domains\Catalog\Infrastructure\Models\DinhMucNgoiHaiVanMieu;
use App\Domains\Catalog\Infrastructure\Models\GachCoBatTrang;
use App\Domains\Catalog\Infrastructure\Models\GachHoaThongGio;
use App\Domains\Catalog\Infrastructure\Models\NgoiHaiVanMieu;
use App\Domains\Catalog\Infrastructure\Models\PhuKienNgoi;
use App\Domains\Catalog\Infrastructure\ProductWriter;
use Illuminate\Support\Facades\Blade;

test('quantity calculator renders side-by-side layout on desktop', function () {
    $html = Blade::render('<x-client.catalog.shared.quantity-calculator />');

    expect($html)
        ->toContain('lg:grid-cols-2')
        ->toContain('id="bang-kich-thuoc"')
        ->toContain('id="cach-tinh-khoi-luong"')
        ->toContain('data-quantity-calculator');
});

test('ngoi hai van mieu calculator renders side-by-side layout on desktop', function () {
    $html = Blade::render('<x-client.catalog.products.ngoi-hai-van-mieu.calculator />');

    expect($html)
        ->toContain('lg:grid-cols-2')
        ->toContain('id="bang-kich-thuoc"')
        ->toContain('id="cach-tinh-khoi-luong"')
        ->toContain('data-hai-vm-calculator');
});

test('gach hoa thong gio detail renders dynamic title, breadcrumb, features, images and side-by-side layout', function () {
    GachHoaThongGio::query()->firstOrCreate([], [
        'video_thumbnail' => 'ghtg/video-thumb.jpg',
    ]);

    $writer = app(ProductWriter::class);
    $product = $writer->create('gach_hoa_thong_gio_ct', [
        'name' => 'Gạch Hoa Thông Gió Dynamic Audit',
        'code' => 'GHTG-AUDIT-001',
        'price' => 38000,
        'images' => ['assets/images/ghtg-audit-photo.jpg'],
        'des' => ['Đặc điểm thông gió chống hắt mưa vượt trội', 'Độ bền nung 1200 độ C'],
        'size' => '200x200x60mm',
        'size_image' => 'assets/images/ghtg-audit-size.png',
        'is_delete' => false,
    ]);

    $response = $this->get(route('client.products.gach-hoa-thong-gio.detail', $product->gach_hoa_thong_gio_ct_id));

    $response->assertOk();
    $content = $response->getContent();

    expect($content)
        ->toContain('Gạch Hoa Thông Gió Dynamic Audit')
        ->toContain('Đặc điểm thông gió chống hắt mưa vượt trội')
        ->toContain('Độ bền nung 1200 độ C')
        ->toContain('ghtg-audit-photo.jpg')
        ->toContain('ghtg-audit-size.png')
        ->toContain('lg:grid-cols-2')
        ->toContain('id="bang-kich-thuoc"')
        ->toContain('id="cach-tinh-khoi-luong"')
        ->not->toContain('Timeless beauty to be treasured');
});

test('gach co bat trang detail renders dynamic title, breadcrumb, features, images and side-by-side layout', function () {
    GachCoBatTrang::query()->firstOrCreate([], [
        'thumbnail_main' => 'gcbt/main.jpg',
    ]);

    $writer = app(ProductWriter::class);
    $product = $writer->create('gach_co_bat_trang_ct', [
        'name' => 'Gạch Cổ Bát Tràng Dynamic Audit',
        'code' => 'GCBT-AUDIT-001',
        'price' => 18000,
        'images' => ['assets/images/gcbt-audit-photo.jpg'],
        'des' => ['Chất gạch thô mộc phong cách cung đình', 'Chống rêu mốc tự nhiên'],
        'size' => '300x150x50mm',
        'size_image' => 'assets/images/gcbt-audit-size.png',
        'is_delete' => false,
    ]);

    $response = $this->get(route('client.products.gach-co-bat-trang.detail', $product->gach_co_bat_trang_ct_id));

    $response->assertOk();
    $content = $response->getContent();

    expect($content)
        ->toContain('Gạch Cổ Bát Tràng Dynamic Audit')
        ->toContain('Chất gạch thô mộc phong cách cung đình')
        ->toContain('Chống rêu mốc tự nhiên')
        ->toContain('gcbt-audit-photo.jpg')
        ->toContain('gcbt-audit-size.png')
        ->toContain('lg:grid-cols-2')
        ->toContain('id="bang-kich-thuoc"')
        ->toContain('id="cach-tinh-khoi-luong"')
        ->not->toContain('Timeless beauty to be treasured');
});

test('ngoi hai van mieu detail renders dynamic title, breadcrumb and side-by-side layout', function () {
    NgoiHaiVanMieu::query()->firstOrCreate([], [
        'thumbnail_main' => 'ngoi-hai/banner-detail.jpg',
        'title1' => 'Tiêu đề 1',
        'thumbnail1' => 'ngoi-hai/thumb-1.jpg',
        'title2' => 'Tiêu đề 2',
        'thumbnail2' => 'ngoi-hai/thumb-2.jpg',
        'title3' => 'Tiêu đề 3',
        'thumbnail3' => 'ngoi-hai/thumb-3.jpg',
    ]);

    DinhMucNgoiHaiVanMieu::query()->firstOrCreate([
        'roof_type' => 'default',
    ], [
        'ngoi_tren_mai_go' => 125,
        'ngoi_tren_mai_be_tong' => 75,
    ]);

    $writer = app(ProductWriter::class);
    $product = $writer->create('ngoi_hai_van_mieu_ct', [
        'name' => 'Ngói Hài Văn Miếu Dynamic Audit',
        'color' => 'Đỏ gốm',
        'price' => 0,
        'images' => ['ngoi-hai/van-mieu-audit.jpg'],
        'des' => ['Dáng vảy cá mũi hài thanh thoát'],
        'size' => 'L280 x W280 x H54mm',
        'size_image' => 'ngoi-hai/size-audit.jpg',
        'is_delete' => false,
    ]);

    $response = $this->get(route('client.products.ngoi-hai-van-mieu.detail', $product->ngoi_hai_van_mieu_ct_id));

    $response->assertOk();
    $content = $response->getContent();

    expect($content)
        ->toContain('Ngói Hài Văn Miếu Dynamic Audit')
        ->toContain('lg:grid-cols-2')
        ->toContain('id="bang-kich-thuoc"')
        ->toContain('id="cach-tinh-khoi-luong"')
        ->toContain('data-hai-vm-calculator');
});

test('ngoi bo noc accessory detail renders dynamic breadcrumb and no english placeholder text', function () {
    PhuKienNgoi::query()->firstOrCreate([], [
        'thumbnail_main' => 'pk/main.jpg',
    ]);

    $writer = app(ProductWriter::class);
    $product = $writer->create('phu_kien_ngoi_ct', [
        'name' => 'Ngói Bò Nóc Dynamic Audit',
        'category_type' => 'ngoi_bo_noc',
        'images' => ['assets/images/bo-noc-audit.jpg'],
        'des' => [],
        'size' => '400mm',
        'is_delete' => false,
    ]);

    $response = $this->get(route('client.products.phu-kien-ngoi.ngoi-bo-noc.detail', $product->phu_kien_ngoi_ct_id));

    $response->assertOk();
    $content = $response->getContent();

    expect($content)
        ->toContain('Ngói Bò Nóc Dynamic Audit')
        ->not->toContain('Timeless beauty to be treasured')
        ->not->toContain('Beginner friendly and improves intelligence');
});
