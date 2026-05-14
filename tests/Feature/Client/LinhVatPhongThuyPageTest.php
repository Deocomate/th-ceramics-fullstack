<?php

use App\Models\LinhVat;
use App\Models\LinhVatPhongThuy;
use App\Models\LinhVatPhongThuyCt;

function createLinhVatPhongThuyPageConfig(): LinhVatPhongThuy
{
    $config = LinhVatPhongThuy::query()->create([
        'thumbnail_main' => 'linh_vat_phong_thuy/images/banner.png',
        'video' => 'https://example.com/video.mp4',
    ]);

    foreach ([
        ['title' => 'Nghê', 'image' => 'assets/images/nghe.png'],
        ['title' => 'Phượng', 'image' => 'assets/images/phuong.png'],
        ['title' => 'Đầu rồng', 'image' => 'assets/images/dau-rong.png'],
    ] as $item) {
        LinhVat::query()->create([
            'title' => $item['title'],
            'image' => $item['image'],
            'description' => $item['title'].' là linh vật phong thủy.',
            'linh_vat_phong_thuy_id' => $config->linh_vat_phong_thuy_id,
        ]);
    }

    return $config;
}

function createLinhVatPhongThuyProduct(array $overrides = []): LinhVatPhongThuyCt
{
    return LinhVatPhongThuyCt::query()->create(array_merge([
        'code' => fake()->unique()->bothify('LVPT-###'),
        'name' => 'Linh vật test',
        'images' => ['assets/images/ngoi-01.jpg'],
        'price' => 675000,
        'des' => [],
        'size' => 'L280 x W280 x H54mm',
        'size_image' => null,
        'size_des' => [],
        'is_delete' => 0,
    ], $overrides));
}

test('client linh vat phong thuy index renders dynamic static-design sections', function () {
    createLinhVatPhongThuyPageConfig();

    for ($i = 1; $i <= 9; $i++) {
        createLinhVatPhongThuyProduct([
            'code' => 'LVPT-'.str_pad((string) $i, 3, '0', STR_PAD_LEFT),
            'name' => 'Linh vật sản phẩm '.$i,
        ]);
    }

    $this->get(route('client.products.linh-vat-phong-thuy.index'))
        ->assertOk()
        ->assertSee('Oai phong bệ vệ trước sân đình')
        ->assertSee('Nghê')
        ->assertSee('Phượng')
        ->assertSee('Đầu rồng')
        ->assertSee('data-product-section', false)
        ->assertSee('swiper-slide', false)
        ->assertSee('col-span-2', false)
        ->assertSee('MSP:');
});

test('client linh vat phong thuy index filters products by search keyword', function () {
    createLinhVatPhongThuyPageConfig();

    createLinhVatPhongThuyProduct([
        'code' => 'LVPT-MATCH',
        'name' => 'Nghê hợp bộ lọc',
        'size' => 'L280 x W280 x H54mm',
    ]);

    createLinhVatPhongThuyProduct([
        'code' => 'LVPT-HIDDEN',
        'name' => 'Sản phẩm bị ẩn',
        'size' => 'L100 x W100 x H30mm',
    ]);

    $this->get(route('client.products.linh-vat-phong-thuy.index', ['search' => 'MATCH']))
        ->assertOk()
        ->assertSee('Nghê hợp bộ lọc')
        ->assertSee('LVPT-MATCH')
        ->assertDontSee('Sản phẩm bị ẩn')
        ->assertDontSee('LVPT-HIDDEN');
});

test('client linh vat phong thuy index sorts products by price ascending', function () {
    createLinhVatPhongThuyPageConfig();

    createLinhVatPhongThuyProduct([
        'code' => 'LVPT-HIGH',
        'name' => 'Sort giá cao',
        'price' => 900000,
    ]);

    createLinhVatPhongThuyProduct([
        'code' => 'LVPT-LOW',
        'name' => 'Sort giá thấp',
        'price' => 100000,
    ]);

    createLinhVatPhongThuyProduct([
        'code' => 'LVPT-MID',
        'name' => 'Sort giá giữa',
        'price' => 500000,
    ]);

    $this->get(route('client.products.linh-vat-phong-thuy.index', ['sort' => 'price_asc']))
        ->assertOk()
        ->assertSeeInOrder(['Sort giá thấp', 'Sort giá giữa', 'Sort giá cao']);
});

test('client linh vat phong thuy index paginates products and preserves filters', function () {
    createLinhVatPhongThuyPageConfig();

    for ($i = 1; $i <= 9; $i++) {
        createLinhVatPhongThuyProduct([
            'code' => 'PAGED-'.str_pad((string) $i, 3, '0', STR_PAD_LEFT),
            'name' => 'Paged linh vật '.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
        ]);
    }

    $this->get(route('client.products.linh-vat-phong-thuy.index', [
        'search' => 'Paged',
        'sort' => 'name_asc',
    ]))
        ->assertOk()
        ->assertSee('Paged linh vật 01')
        ->assertSee('aria-label="Pagination"', false)
        ->assertSee('border-b-[3px] border-black', false)
        ->assertSee('search=Paged', false)
        ->assertSee('sort=name_asc', false)
        ->assertSee('page=2', false);

    $this->get(route('client.products.linh-vat-phong-thuy.index', [
        'search' => 'Paged',
        'sort' => 'name_asc',
        'page' => 2,
    ]))
        ->assertOk()
        ->assertSee('Paged linh vật 09')
        ->assertDontSee('Paged linh vật 01');
});
