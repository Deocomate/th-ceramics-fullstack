<?php

use App\Models\GachCoBatTrang;
use App\Models\GachCoBatTrangCt;

beforeEach(function () {
    GachCoBatTrang::query()->create([
        'thumbnail_main' => 'assets/images/gach-co-banner.png',
        'video' => null,
        'images' => [],
        'section_bat' => [
            'title' => 'Gạch Bát Config',
            'subtitle' => 'Subtitle Bát',
            'description' => 'Mô tả Gạch Bát',
            'colors' => ['#111111', '#222222', '#333333'],
            'gallery' => ['assets/images/gach-bat-01.jpg'],
        ],
        'section_that' => [
            'title' => 'Gạch Thất Config',
            'subtitle' => 'Subtitle Thất',
            'description' => 'Mô tả Gạch Thất',
            'colors' => ['#444444', '#555555', '#666666'],
            'gallery' => ['assets/images/gach-that-01.jpg'],
        ],
        'section_the' => [
            'title' => 'Gạch Thẻ Config',
            'subtitle' => 'Subtitle Thẻ',
            'description' => 'Mô tả Gạch Thẻ',
            'colors' => ['#777777', '#888888', '#999999'],
            'gallery' => ['assets/images/gach-the-01.jpg'],
        ],
    ]);
});

function createGachCoBatTrangProduct(array $overrides = []): GachCoBatTrangCt
{
    return GachCoBatTrangCt::query()->create(array_merge([
        'code' => fake()->unique()->bothify('GCB-###'),
        'name' => 'Sản phẩm động',
        'category_type' => 'bat',
        'images' => ['assets/images/gach-bat-01.jpg'],
        'price' => 10000,
        'des' => [],
        'size' => '10 x 20 cm',
        'dinh_muc' => '11',
        'weight' => '1.2',
        'is_delete' => 0,
    ], $overrides));
}

test('client page groups products by category type instead of name', function () {
    createGachCoBatTrangProduct([
        'code' => 'BAT-001',
        'name' => 'Tên không chứa keyword',
        'category_type' => 'bat',
        'price' => 9000,
    ]);

    createGachCoBatTrangProduct([
        'code' => 'THE-001',
        'name' => 'Một sản phẩm bất kỳ',
        'category_type' => 'the',
        'price' => 18000,
    ]);

    $this->get(route('client.products.gach-co-bat-trang.index'))
        ->assertOk()
        ->assertSee('Gạch Bát Config')
        ->assertSee('Gạch Thẻ Config')
        ->assertSee('Tên không chứa keyword')
        ->assertSee('Một sản phẩm bất kỳ');
});

test('client type filter hides empty sections and sorts products', function () {
    createGachCoBatTrangProduct([
        'code' => 'BAT-LOW',
        'name' => 'Bát Giá Thấp',
        'category_type' => 'bat',
        'price' => 9000,
    ]);

    createGachCoBatTrangProduct([
        'code' => 'BAT-HIGH',
        'name' => 'Bát Giá Cao',
        'category_type' => 'bat',
        'price' => 19000,
    ]);

    createGachCoBatTrangProduct([
        'code' => 'THE-HIDDEN-BY-FILTER',
        'name' => 'Thẻ bị lọc',
        'category_type' => 'the',
        'price' => 12000,
    ]);

    $response = $this->get(route('client.products.gach-co-bat-trang.index', [
        'type' => 'bat',
        'sort' => 'price_asc',
    ]));

    $response->assertOk()
        ->assertSee('Gạch Bát Config')
        ->assertDontSee('Gạch Thẻ Config')
        ->assertSeeInOrder(['Bát Giá Thấp', 'Bát Giá Cao']);
});

test('client recommendations use recent products with fallback to active products', function () {
    $recent = createGachCoBatTrangProduct([
        'code' => 'RECENT-001',
        'name' => 'Sản phẩm vừa xem',
        'category_type' => 'bat',
    ]);

    createGachCoBatTrangProduct([
        'code' => 'FALLBACK-001',
        'name' => 'Sản phẩm fallback',
        'category_type' => 'bat',
    ]);

    $this->withSession([
        'th_recent_products' => [
            ['type' => 'gach_co_bat_trang_ct', 'id' => $recent->gach_co_bat_trang_ct_id],
        ],
    ])
        ->get(route('client.products.gach-co-bat-trang.index'))
        ->assertOk()
        ->assertSee('Sản phẩm vừa xem');

    $this->get(route('client.products.gach-co-bat-trang.index'))
        ->assertOk()
        ->assertSee('Sản phẩm fallback');
});
