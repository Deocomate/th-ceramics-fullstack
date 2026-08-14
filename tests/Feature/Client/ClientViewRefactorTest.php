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
        ->toContain('href="'.route('client.home').'"')
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
        ->toContain('data-product-id="7"')
        ->toContain('js-add-to-cart')
        ->toContain('Thêm vào giỏ');
});

test('recommendations show add to cart when price is zero', function () {
    $html = Blade::render(
        '<x-client.shared.recommendations :related-products="$products" route-name="client.products.gach-co-bat-trang.detail" pk-field="id" product-type="gach_co_bat_trang_ct" />',
        [
            'products' => collect([
                (object) [
                    'id' => 9,
                    'name' => 'Gạch liên hệ',
                    'images' => [],
                    'price' => 0,
                ],
            ]),
        ],
    );

    expect($html)->toContain('js-add-to-cart');
});

test('product card shows add to cart by default when type and id provided', function () {
    $html = Blade::render(
        '<x-client.shared.product-card href="#" title="Test" product-type="gach_co_bat_trang_ct" :product-id="7" />'
    );

    expect($html)
        ->toContain('js-add-to-cart')
        ->toContain('data-product-type="gach_co_bat_trang_ct"')
        ->toContain('data-product-id="7"')
        ->toContain('Thêm vào giỏ');
});

test('product card includes variant id when provided', function () {
    $html = Blade::render(
        '<x-client.shared.product-card href="#" title="Đèn" product-type="den_vuon_gom_su_ct" :product-id="3" :variant-id="12" />'
    );

    expect($html)
        ->toContain('data-product-type="den_vuon_gom_su_ct"')
        ->toContain('data-variant-id="12"');
});

test('product listing components use shared product card markup', function () {
    $gridSource = file_get_contents(resource_path('views/components/client/shared/product-grid.blade.php'));
    $recommendationsSource = file_get_contents(resource_path('views/components/client/shared/recommendations.blade.php'));

    expect($gridSource)
        ->toContain('<x-client.shared.product-card')
        ->not->toContain('product-card relative bg-white rounded-sm')
        ->and($recommendationsSource)
        ->toContain('<x-client.shared.product-card')
        ->not->toContain('product-card relative bg-white rounded-sm');
});

test('product grid renders shared product card and add to cart data', function () {
    $html = Blade::render(
        '<x-client.shared.product-grid :products="$products" route-name="client.products.gach-co-bat-trang.detail" pk-field="id" product-type="gach_co_bat_trang_ct" />',
        [
            'products' => collect([
                (object) [
                    'id' => 7,
                    'name' => 'Grid test product',
                    'code' => 'GRID-001',
                    'images' => [],
                    'price' => 125000,
                ],
            ]),
        ],
    );

    expect($html)
        ->toContain('Grid test product')
        ->toContain('MSP: GRID-001')
        ->toContain('125.000')
        ->toContain('product-overlay')
        ->toContain('eye.svg')
        ->toContain('href="'.route('client.products.gach-co-bat-trang.detail', 7).'"')
        ->toContain('data-product-type="gach_co_bat_trang_ct"')
        ->toContain('data-product-id="7"')
        ->toContain('js-add-to-cart');
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
        ->toContain('data-add-to-cart-url="'.route('client.cart.add').'"')
        ->toContain('data-product-main-swiper')
        ->toContain('data-detail-sku')
        ->toContain('data-product-variant')
        ->toContain('data-detail-add-to-cart');
});

test('calculator components keep root data attributes without inline scripts', function () {
    $quantityHtml = Blade::render('<x-client.shared.quantity-calculator />');
    $haiHtml = Blade::render('<x-client.products.ngoi-hai-van-mieu.calculator />');

    expect($quantityHtml)
        ->toContain('data-quantity-calculator')
        ->not->toContain('@push')
        ->not->toContain('<script>');

    expect($haiHtml)
        ->toContain('data-hai-vm-calculator')
        ->toContain('id="cach-tinh-khoi-luong"')
        ->toContain('id="bang-kich-thuoc"')
        ->not->toContain('@push')
        ->not->toContain('<script>');
});

test('product guide tabs and weight sticky bar expose data hooks', function () {
    $tabsHtml = Blade::render(
        '<x-client.shared.product-guide-tabs><x-slot:install>install-body</x-slot:install><x-slot:applications>apps-body</x-slot:applications></x-client.shared.product-guide-tabs>'
    );
    $barHtml = Blade::render('<x-client.shared.weight-calculator-sticky-bar />');
    $quantityHtml = Blade::render('<x-client.shared.quantity-calculator />');

    expect($tabsHtml)
        ->toContain('data-product-guide-tabs')
        ->toContain('data-guide-tabs-swiper')
        ->toContain('install-body')
        ->toContain('apps-body');

    expect($barHtml)
        ->toContain('data-weight-calculator-bar')
        ->toContain('#cach-tinh-khoi-luong')
        ->toContain('Tính khối lượng')
        ->toContain('bottom-20')
        ->not->toContain('-translate-x-1/2');

    expect($quantityHtml)
        ->toContain('id="cach-tinh-khoi-luong"')
        ->toContain('id="bang-kich-thuoc"')
        ->toContain('data-quantity-calculator');
});
