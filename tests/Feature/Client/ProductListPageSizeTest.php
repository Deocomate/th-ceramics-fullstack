<?php

use App\Domains\Catalog\Infrastructure\Models\GachHoaThongGio;
use App\Domains\Catalog\Infrastructure\Models\NgoiAmDuong;
use App\Domains\Catalog\Infrastructure\Models\NgoiHaiVanMieu;
use App\Domains\Catalog\Infrastructure\ProductWriter;

// Gạch trang trí, linh vật phong thủy and đèn gốm sứ assert their page size in their own page tests.
dataset('paginated product list pages', [
    'ngoi am duong' => [
        'client.products.ngoi-am-duong.index',
        'ngoi_am_duong_ct',
        NgoiAmDuong::class,
        ['thumbnail_main' => 'seeders/products/cover.png', 'thumbnail1' => 'seeders/products/cover.png', 'thumbnail2' => 'seeders/products/cover.png'],
    ],
    'ngoi hai van mieu' => [
        'client.products.ngoi-hai-van-mieu.index',
        'ngoi_hai_van_mieu_ct',
        NgoiHaiVanMieu::class,
        [
            'thumbnail_main' => 'ngoi-hai/banner.jpg',
            'title1' => 'Tiêu đề 1', 'thumbnail1' => 'ngoi-hai/thumb-1.jpg',
            'title2' => 'Tiêu đề 2', 'thumbnail2' => 'ngoi-hai/thumb-2.jpg',
            'title3' => 'Tiêu đề 3', 'thumbnail3' => 'ngoi-hai/thumb-3.jpg',
            'video' => null,
            'images' => ['ngoi-hai/cong-doan-01.jpg'],
        ],
    ],
    'gach hoa thong gio' => [
        'client.products.gach-hoa-thong-gio.index',
        'gach_hoa_thong_gio_ct',
        GachHoaThongGio::class,
        ['video_thumbnail' => 'ghtg/video-thumb.jpg'],
    ],
]);

test('product list pages show twelve products per page', function (string $routeName, string $typeKey, string $configModel, array $configAttributes) {
    $configModel::query()->firstOrCreate([], $configAttributes);

    foreach (range(1, 13) as $index) {
        app(ProductWriter::class)->create($typeKey, [
            'code' => sprintf('PAGE-SIZE-%03d', $index),
            'name' => sprintf('Sản phẩm kiểm thử %02d', $index),
            'images' => ['assets/images/trang-tri-01.png'],
            'price' => 100000 + $index,
            'des' => [],
            'size' => '10 x 20 cm',
            'is_delete' => 0,
        ]);
    }

    $this->get(route($routeName, ['sort' => 'name_asc']))
        ->assertOk()
        ->assertSee('Sản phẩm kiểm thử 01')
        ->assertSee('Sản phẩm kiểm thử 12')
        ->assertDontSee('Sản phẩm kiểm thử 13')
        ->assertSee('page=2', false);

    $this->get(route($routeName, ['sort' => 'name_asc', 'page' => 2]))
        ->assertOk()
        ->assertSee('Sản phẩm kiểm thử 13')
        ->assertDontSee('Sản phẩm kiểm thử 12');
})->with('paginated product list pages');
