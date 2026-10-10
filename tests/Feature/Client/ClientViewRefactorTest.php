<?php

use App\Domains\Catalog\Domain\PhuKienNgoiCategory;
use App\Domains\Catalog\Infrastructure\Models\Product;
use App\Domains\Catalog\Infrastructure\ProductWriter;
use App\Domains\Commerce\Infrastructure\Mail\ConsultationConfirmationMail;
use App\Domains\Commerce\Infrastructure\Mail\ConsultationRequestedMail;
use App\Domains\Commerce\Infrastructure\Mail\OrderCreatedMail;
use App\Domains\Commerce\Infrastructure\Mail\OrderStatusUpdatedMail;
use App\Domains\Commerce\Infrastructure\Models\ConsultationRequest;
use App\Domains\Commerce\Infrastructure\Models\Order;
use App\Domains\Commerce\Infrastructure\Models\OrderItem;
use App\Domains\Content\Domain\ContentPageRegistry;
use App\Domains\Content\Infrastructure\Mail\ContactFormMail;
use App\Domains\Content\Infrastructure\Models\Catalog;
use App\Domains\Identity\Infrastructure\Notifications\ResetPasswordNotification;
use App\Domains\Identity\Infrastructure\Notifications\VerifyEmailQueued;
use App\Domains\Identity\Infrastructure\Models\User;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;

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
        ->toContain('125.000 đ/viên')
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

test('product card shows the correct unit for each product type', function () {
    foreach ([
        'ngoi_am_duong_ct' => 'm²',
        'gach_trang_tri_ct' => 'viên',
        'phu_kien_ngoi_ct' => 'chiếc',
    ] as $type => $unit) {
        $html = Blade::render(
            '<x-client.shared.product-card title="Sản phẩm" price="Giá: 120.000 đ/m²" :product-type="$type" />',
            ['type' => $type],
        );

        expect($html)->toContain('Giá: 120.000 đ/'.$unit);
    }

    $html = Blade::render(
        '<x-client.shared.product-card title="Gạch" price="120.000đ" :product="$product" />',
        ['product' => new Product(['type_key' => 'gach_trang_tri_ct'])],
    );

    expect($html)->toContain('120.000 đ/viên');
});

test('product listing components use shared product card markup', function () {
    $gridSource = file_get_contents(resource_path('views/components/client/catalog/shared/product-grid.blade.php'));
    $recommendationsSource = file_get_contents(resource_path('views/components/client/catalog/shared/recommendations.blade.php'));

    expect($gridSource)
        ->toContain('<x-client.catalog.shared.product-card')
        ->not->toContain('product-card relative bg-white rounded-sm')
        ->and($recommendationsSource)
        ->toContain('<x-client.catalog.shared.product-card')
        ->not->toContain('product-card relative bg-white rounded-sm');
});

test('product grid renders shared product card and add to cart data', function () {
    $html = Blade::render(
        '<x-client.catalog.shared.product-grid :products="$products" route-name="client.products.gach-co-bat-trang.detail" pk-field="id" product-type="gach_co_bat_trang_ct" />',
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
    $quantityHtml = Blade::render('<x-client.catalog.shared.quantity-calculator />');
    $haiHtml = Blade::render('<x-client.catalog.products.ngoi-hai-van-mieu.calculator />');

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
    $barHtml = Blade::render('<x-client.catalog.shared.weight-calculator-sticky-bar />');
    $quantityHtml = Blade::render('<x-client.catalog.shared.quantity-calculator />');

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

test('all registered customer service pages and flipbook render successfully', function () {
    $user = User::factory()->create();
    foreach (ContentPageRegistry::VALID_CUSTOMER_SERVICE_PAGES as $page) {
        $req = $page === 'tai-khoan-cua-toi' ? $this->actingAs($user) : $this;
        $req->get(route('client.customer-service.show', $page))
            ->assertSuccessful();
    }
    $catalog = Catalog::query()->create([
        'tieu_de' => 'Sample Catalog',
        'file' => 'catalogs/sample.pdf',
        'anh_dai_dien' => null,
    ]);
    $this->get(route('client.dich-vu.tai-catalog.read', ['id' => $catalog->catalog_id]))->assertSuccessful();
});

test('ngoi hai co and accessory subtype detail routes render canonical products', function () {
    $nhc = app(ProductWriter::class)->create('ngoi_hai_co_ct', [
        'name' => 'Ngói Hài Cổ Baseline',
        'code' => 'NHC-BASE',
        'price' => 150000,
        'images' => [],
        'is_delete' => false,
    ]);
    $this->get(route('client.products.ngoi-hai-co.detail', $nhc->public_id))
        ->assertSuccessful()
        ->assertSee('Ngói Hài Cổ Baseline');

    $boNoc = app(ProductWriter::class)->create('phu_kien_ngoi_ct', [
        'name' => 'Bờ Nóc Baseline',
        'code' => 'NBN-BASE',
        'price' => 50000,
        'category_type' => PhuKienNgoiCategory::TYPE_BO_NOC,
        'images' => [],
        'is_delete' => false,
    ]);
    $this->get(route('client.products.phu-kien-ngoi.ngoi-bo-noc.detail', $boNoc->public_id))
        ->assertSuccessful()
        ->assertSee('Bờ Nóc Baseline');

    $chuVan = app(ProductWriter::class)->create('phu_kien_ngoi_ct', [
        'name' => 'Chữ Vạn Baseline',
        'code' => 'BNCV-BASE',
        'price' => 60000,
        'category_type' => PhuKienNgoiCategory::TYPE_CHU_VAN,
        'images' => [],
        'is_delete' => false,
    ]);
    $this->get(route('client.products.phu-kien-ngoi.bo-noc-chu-van.detail', $chuVan->public_id))
        ->assertSuccessful()
        ->assertSee('Chữ Vạn Baseline');
});

test('all seven markdown email templates render successfully with expected content', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'user@example.com',
    ]);

    // 1. Auth reset password notification
    $resetNotif = new ResetPasswordNotification('fake-token');
    $mailMsg1 = $resetNotif->toMail($user);
    $html1 = (string) app(Markdown::class)->render($mailMsg1->markdown, $mailMsg1->data());
    expect($html1)->toContain('fake-token');

    // 2. Auth verify email notification
    $verifyNotif = new VerifyEmailQueued;
    $mailMsg2 = $verifyNotif->toMail($user);
    $html2 = (string) app(Markdown::class)->render($mailMsg2->markdown, $mailMsg2->data());
    expect($html2)->toContain('Xác thực email');

    // 3. Consultation requested
    $consultation = ConsultationRequest::query()->create([
        'customer_name' => 'Nguyen Van B',
        'phone' => '0988776655',
        'email' => 'consult@example.com',
        'note' => 'Cần tư vấn ngói lợp',
        'status' => 'pending',
    ]);
    $mailRequested = new ConsultationRequestedMail($consultation);
    $html3 = $mailRequested->render();
    expect($html3)->toContain('Nguyen Van B')->toContain('0988776655');

    // 4. Consultation confirmation
    $mailConfirmation = new ConsultationConfirmationMail($consultation);
    $html4 = $mailConfirmation->render();
    expect($html4)->toContain('Cần tư vấn ngói lợp');

    // 5. Contact form
    $contactMail = new ContactFormMail([
        'name' => 'Tran Thi C',
        'email' => 'contact@example.com',
        'phone' => '0912345678',
        'message' => 'Hỏi giá đèn gốm sứ',
    ]);
    $html5 = $contactMail->render();
    expect($html5)->toContain('Tran Thi C')->toContain('Hỏi giá đèn gốm sứ');

    // 6. Order created
    $order = Order::factory()->create([
        'order_code' => 'ORD-SMOKE-100',
        'customer_name' => 'Le Van D',
        'phone' => '0933221100',
        'email' => 'buyer@example.com',
        'address' => '123 Pho Hue',
        'subtotal' => 200000,
        'discount' => 0,
        'shipping_fee' => 30000,
        'total_amount' => 230000,
        'status' => 'processing',
    ]);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_name' => 'Gạch cổ Bát Tràng',
        'quantity' => 2,
        'price' => 100000,
        'total' => 200000,
    ]);
    $orderCreatedMail = new OrderCreatedMail($order->fresh(['items']));
    $html6 = $orderCreatedMail->render();
    expect($html6)->toContain('ORD-SMOKE-100')->toContain('Gạch cổ Bát Tràng');

    // 7. Order status updated
    $orderStatusMail = new OrderStatusUpdatedMail($order->fresh(['items']));
    $html7 = $orderStatusMail->render();
    expect($html7)->toContain('ORD-SMOKE-100');
});

test('two independent user requests do not share cart count or cart state', function () {
    $product = app(ProductWriter::class)->create('gach_co_bat_trang_ct', [
        'name' => 'Gạch test isolation',
        'code' => 'GT-ISO-01',
        'price' => 100000,
        'images' => [],
        'is_delete' => 0,
    ]);

    $responseA = $this->withSession(['cart' => []])
        ->postJson(route('client.cart.add'), [
            'product_type' => 'gach_co_bat_trang_ct',
            'product_id' => $product->id,
            'qty' => 3,
        ]);
    $responseA->assertSuccessful()->assertJsonPath('cart_count', 3);

    $this->flushSession();

    $responseB = $this->getJson(route('client.cart.mini'));
    $responseB->assertSuccessful()->assertJsonPath('cart_count', 0);
});

test('authenticated user session does not leak auth state to separate guest request', function () {
    $user = User::factory()->create([
        'role' => 'customer',
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('client.auth.profile'))
        ->assertSuccessful();

    $this->flushSession();
    $this->app['auth']->forgetGuards();

    $this->get(route('client.auth.profile'))
        ->assertRedirect(route('client.auth.login'));
});

test('home and product detail pages execute bounded queries without duplicate N+1 queries', function () {
    DB::enableQueryLog();
    DB::flushQueryLog();

    $this->get(route('client.home'))->assertSuccessful();
    $homeQueries = DB::getQueryLog();
    $homeCount = count($homeQueries);
    expect($homeCount)->toBeGreaterThan(0)->toBeLessThan(35);

    $homeSqls = array_map(fn ($q) => $q['query'], $homeQueries);
    $homeSqlCounts = array_count_values($homeSqls);
    foreach ($homeSqlCounts as $sql => $count) {
        expect($count)->toBeLessThan(4, "Query repeated too many times on home: {$sql}");
    }

    $product = app(ProductWriter::class)->create('ngoi_am_duong_ct', [
        'name' => 'Ngói Âm Dương Query Check',
        'code' => 'NAD-QRY-01',
        'price' => 180000,
        'images' => ['assets/images/ngoi-01.jpg'],
        'is_delete' => 0,
    ]);

    DB::flushQueryLog();

    $this->get(route('client.products.ngoi-am-duong.detail', $product->public_id))
        ->assertSuccessful();
    $detailQueries = DB::getQueryLog();
    $detailCount = count($detailQueries);
    expect($detailCount)->toBeGreaterThan(0)->toBeLessThan(25);

    $detailSqls = array_map(fn ($q) => $q['query'], $detailQueries);
    $detailSqlCounts = array_count_values($detailSqls);
    foreach ($detailSqlCounts as $sql => $count) {
        expect($count)->toBeLessThan(4, "Detail query repeated too many times: {$sql}");
    }
});

test('view component fallbacks query only when data was not supplied', function () {
    DB::enableQueryLog();

    DB::flushQueryLog();
    $emptyCouponHtml = Blade::render(
        '<x-client.commerce.shared.coupon-banner :banner-coupons="$items" />',
        ['items' => collect()],
    );
    expect($emptyCouponHtml)->not->toContain('Ưu đãi:');
    expect(collect(DB::getQueryLog())->pluck('query')->filter(
        fn (string $sql): bool => str_contains($sql, '"coupons"'),
    ))->toBeEmpty();

    DB::flushQueryLog();
    $emptyAwardsHtml = Blade::render(
        '<x-client.content.home.awards-deck :awards="$items" />',
        ['items' => collect()],
    );
    expect($emptyAwardsHtml)->not->toContain('awards-component');
    expect(collect(DB::getQueryLog())->pluck('query')->filter(
        fn (string $sql): bool => str_contains($sql, '"giai_thuong_thanh_tuu"'),
    ))->toBeEmpty();

    DB::flushQueryLog();
    Blade::render('<x-client.commerce.shared.coupon-banner />');
    expect(collect(DB::getQueryLog())->pluck('query')->filter(
        fn (string $sql): bool => str_contains($sql, '"coupons"'),
    ))->toHaveCount(1);

    DB::flushQueryLog();
    Blade::render('<x-client.content.home.awards-deck />');
    expect(collect(DB::getQueryLog())->pluck('query')->filter(
        fn (string $sql): bool => str_contains($sql, '"giai_thuong_thanh_tuu"'),
    ))->toHaveCount(1);
});

test('product json ld stays valid and cannot close its script element', function () {
    $payload = '</script><script>window.jsonLdXss = true</script>';
    $railing = app(ProductWriter::class)->create('lan_can_gom_su_ct', [
        'name' => $payload,
        'code' => 'LCGS-XSS',
        'price' => 100000,
        'images' => [],
        'is_delete' => 0,
    ]);
    $response = $this->get(route('client.products.lan-can-gom-su.detail', $railing->public_id));
    $response
        ->assertSuccessful()
        ->assertDontSee('</script><script>window.jsonLdXss = true</script>', false)
        ->assertSee('\\u003C/script\\u003E', false);

    preg_match('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/s', $response->getContent(), $matches);
    $jsonLd = json_decode($matches[1] ?? '', true, 512, JSON_THROW_ON_ERROR);

    expect($jsonLd['name'])->toBe($payload);
});
