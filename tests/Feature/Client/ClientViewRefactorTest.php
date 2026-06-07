<?php

use Illuminate\Support\Facades\Blade;

test('generic breadcrumb renders linked items and current item', function () {
    $html = Blade::render('<x-client.shared.breadcrumb :items="$items" />', [
        'items' => [
            ['label' => 'Trang chủ', 'url' => route('client.home')],
            ['label' => 'Dịch vụ khách hàng', 'url' => route('client.customer-service.show', 'trang-thai-don-hang')],
            ['label' => 'Trạng thái đơn hàng'],
        ],
    ]);

    expect($html)
        ->toContain('aria-label="Breadcrumb"')
        ->toContain('href="' . route('client.home') . '"')
        ->toContain('Dịch vụ khách hàng')
        ->toContain('Trạng thái đơn hàng');
});

test('customer service pages use shared breadcrumb labels', function () {
    $this->get(route('client.customer-service.show', 'chinh-sach-doi-tra'))
        ->assertSuccessful()
        ->assertSee('Dịch vụ khách hàng')
        ->assertSee('Chính sách đổi trả');
});

test('recommendations render normalized product card and add to cart data', function () {
    $html = Blade::render(
        '<x-client.shared.recommendations :related-products="$products" route-name="client.products.gach-co-bat-trang.detail" pk-field="id" product-type="gach_co_bat_trang_ct" />',
        [
            'products' => collect([
                (object) [
                    'id' => 7,
                    'name' => 'Gạch thử nghiệm',
                    'images' => [],
                    'price' => 125000,
                    'color' => null,
                    'size' => '20 x 20',
                ],
            ]),
        ],
    );

    expect($html)
        ->toContain('Gạch thử nghiệm')
        ->toContain('assets/images/gach-co-work-2.jpg')
        ->toContain('125.000 đ/m²')
        ->toContain('data-product-type="gach_co_bat_trang_ct"')
        ->toContain('data-product-id="7"');
});

test('product detail component exposes scoped data hooks', function () {
    $html = Blade::render(
        '<x-client.shared.product-detail-container title="Sản phẩm thử" price="125.000 đ/m²" raw-price="125000" sku="SKU-001" product-type="gach_co_bat_trang_ct" product-id="7" :images="$images" :colors="$colors" />',
        [
            'images' => ['assets/images/ngoi-01.jpg'],
            'colors' => [
                [
                    'name' => 'Men nâu',
                    'variantId' => 3,
                    'sku' => 'SKU-002',
                    'price' => 130000,
                    'priceFormatted' => '130.000 đ/m²',
                    'image' => asset('assets/images/ngoi-01.jpg'),
                    'colorCode' => '#7a3f1d',
                ],
            ],
        ],
    );

    expect($html)
        ->toContain('data-product-detail-container')
        ->toContain('data-add-to-cart-url="' . route('client.cart.add') . '"')
        ->toContain('data-product-main-swiper')
        ->toContain('data-detail-sku')
        ->toContain('data-product-variant')
        ->toContain('data-detail-add-to-cart');
});

test('calculator components keep root data attributes without inline scripts', function () {
    $quantityHtml = Blade::render('<x-client.shared.quantity-calculator />');
    $haiHtml = Blade::render("<x-client.products.ngoi-hai-van-mieu.calculator />");

    expect($quantityHtml)
        ->toContain('data-quantity-calculator')
        ->not->toContain('@push')
        ->not->toContain('<script>');

    expect($haiHtml)
        ->toContain('data-hai-vm-calculator')
        ->not->toContain('@push')
        ->not->toContain('<script>');
});
