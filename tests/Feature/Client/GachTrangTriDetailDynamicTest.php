<?php

use App\Domains\Catalog\Infrastructure\ProductWriter;
use App\Domains\Catalog\Infrastructure\Models\GachTrangTri;

test('gach trang tri detail page renders dynamic product data without static dummy content', function () {
    GachTrangTri::query()->create([
        'thumbnail_main' => 'assets/images/gach-trang-tri-banner.png',
        'video' => null,
        'images' => ['assets/images/trang-tri-01.png'],
        'ung_dung_da_dang' => [],
    ]);

    $product = app(ProductWriter::class)->create('gach_trang_tri_ct', [
        'code' => 'GTT-DYNAMIC-999',
        'name' => 'Gạch Trang Trí Men Hỏa Biến Dynamic VIP',
        'images' => [
            'seeders/products/gach-trang-tri-chi-tiet/trang-tri-slide-01.jpg',
            'seeders/products/gach-trang-tri-chi-tiet/trang-tri-slide-02.jpg',
        ],
        'price' => 35000,
        'des' => [
            'Chế tác thủ công từ đất sét nguyên chất Bát Tràng',
            'Nung ở nhiệt độ cao 1250 độ C bền đẹp theo thời gian',
        ],
        'size' => '10 x 20 cm',
        'size_image' => 'seeders/products/gach-trang-tri/gach-detail.png',
        'is_delete' => 0,
    ]);

    $response = $this->get(route('client.products.gach-trang-tri.detail', $product->gach_trang_tri_ct_id));

    $response->assertOk();

    // 1. Title and breadcrumb must be dynamic
    $response->assertSee('Gạch Trang Trí Men Hỏa Biến Dynamic VIP - Gạch Trang Trí | Gốm Sứ Thanh Hải');
    $response->assertSee('GTT-DYNAMIC-999');
    $response->assertSee('35.000 đ/viên');

    // 2. Dynamic description / features from DB, NO static english placeholder
    $response->assertSee('Chế tác thủ công từ đất sét nguyên chất Bát Tràng');
    $response->assertSee('Nung ở nhiệt độ cao 1250 độ C bền đẹp theo thời gian');
    $response->assertDontSee('Timeless beauty to be treasured');
    $response->assertDontSee('Beginner friendly and improves intelligence');

    // 3. Dynamic images in swiper gallery
    $response->assertSee('trang-tri-slide-01.jpg');
    $response->assertSee('trang-tri-slide-02.jpg');
    $response->assertDontSee('assets/images/gach-bat-detail-1.png');

    // 4. Product Type & ID bound to container
    $response->assertSee('data-product-type="gach_trang_tri_ct"', false);
    $response->assertSee('data-product-id="'.$product->gach_trang_tri_ct_id.'"', false);

    // 5. Schema.org Product JSON-LD structured data
    $response->assertSee('"@type": "Product"', false);
    $response->assertSee('"name": "Gạch Trang Trí Men Hỏa Biến Dynamic VIP"', false);
});
