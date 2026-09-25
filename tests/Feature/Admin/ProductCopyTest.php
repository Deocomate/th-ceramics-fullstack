<?php

use App\Models\GachHoaThongGioCt;
use App\Models\NgoiAmDuongCt;
use App\Models\User;

test('guest cannot access product copy api', function () {
    $this->getJson(route('admin.product-copy.list', ['type' => 'ngoi-am-duong-ct']))
        ->assertUnauthorized();

    $this->getJson(route('admin.product-copy.detail', ['type' => 'ngoi-am-duong-ct', 'id' => 1]))
        ->assertUnauthorized();
});

test('admin can search and list products for copy', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $product = NgoiAmDuongCt::query()->create([
        'code' => 'NAD-COPY-TEST-001',
        'name' => 'Ngói Âm Dương Thử Nghiệm Sao Chép',
        'color' => 'Xanh Ngọc',
        'price' => 25000,
        'size' => '200x200mm',
        'des' => ['Dòng thông số 1', 'Dòng thông số 2'],
        'images' => ['products/sample.png'],
        'is_delete' => 0,
    ]);

    $response = $this->getJson(route('admin.product-copy.list', [
        'type' => 'ngoi-am-duong-ct',
        'q' => 'Thử Nghiệm',
    ]));

    $response->assertOk()
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonFragment([
            'id' => $product->ngoi_am_duong_ct_id,
            'name' => 'Ngói Âm Dương Thử Nghiệm Sao Chép',
            'code' => 'NAD-COPY-TEST-001',
        ]);
});

test('admin can get product detail for copy with formatted data', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $product = GachHoaThongGioCt::query()->create([
        'code' => 'GHTG-COPY-001',
        'name' => 'Gạch Hoa Thông Gió Bánh Chưng',
        'color' => 'Trắng Sứ',
        'price' => 35000,
        'size' => '300x300mm',
        'des' => ['Chống nóng tuyệt đối', 'Độ bền cao', 'Hoa văn truyền thống'],
        'images' => ['products/sample.png'],
        'is_delete' => 0,
    ]);

    $response = $this->getJson(route('admin.product-copy.detail', [
        'type' => 'gach-hoa-thong-gio-ct',
        'id' => $product->gach_hoa_thong_gio_ct_id,
    ]));

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $product->gach_hoa_thong_gio_ct_id,
                'name' => 'Gạch Hoa Thông Gió Bánh Chưng',
                'code' => 'GHTG-COPY-001',
                'suggested_code' => 'GHTG-COPY-001-COPY',
                'color' => 'Trắng Sứ',
                'price' => 35000,
                'size' => '300x300mm',
                'des' => ['Chống nóng tuyệt đối', 'Độ bền cao', 'Hoa văn truyền thống'],
            ],
        ]);
});

test('product detail returns 404 for invalid product id', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->getJson(route('admin.product-copy.detail', [
        'type' => 'ngoi-am-duong-ct',
        'id' => 999999,
    ]))->assertNotFound();
});

test('admin create page loads with copy_from parameter and renders modal', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $product = NgoiAmDuongCt::query()->create([
        'code' => 'NAD-PRELOAD-001',
        'name' => 'Ngói Âm Dương Nạp Sẵn',
        'color' => 'Men Cổ',
        'price' => 45000,
        'size' => '180x180mm',
        'des' => ['Độ bền 100 năm'],
        'images' => ['products/sample.png'],
        'is_delete' => 0,
    ]);

    $response = $this->get(route('admin.ngoi-am-duong-ct.create', [
        'copy_from' => $product->ngoi_am_duong_ct_id,
    ]));

    $response->assertOk()
        ->assertSee('Sao chép từ sản phẩm cũ')
        ->assertSee('product-copy-modal')
        ->assertSee('NAD-PRELOAD-001-COPY');
});

test('admin index page renders duplicate button linking to create with copy_from', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $product = NgoiAmDuongCt::query()->create([
        'code' => 'NAD-INDEX-DUP-001',
        'name' => 'Sản phẩm kiểm tra nút nhân bản',
        'color' => 'Tự chọn',
        'price' => 12000,
        'images' => ['products/sample.png'],
        'is_delete' => 0,
    ]);

    $response = $this->get(route('admin.ngoi-am-duong-ct.index'));

    $response->assertOk()
        ->assertSee(route('admin.ngoi-am-duong-ct.create', ['copy_from' => $product->ngoi_am_duong_ct_id]));
});

test('deleted product is excluded from search and detail endpoints', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $deletedProduct = NgoiAmDuongCt::query()->create([
        'code' => 'NAD-DELETED-001',
        'name' => 'Ngói Âm Dương Đã Xóa',
        'color' => 'Đỏ Cổ',
        'price' => 20000,
        'images' => ['products/sample.png'],
        'is_delete' => 1,
    ]);

    // Search should not return deleted product
    $searchResponse = $this->getJson(route('admin.product-copy.list', [
        'type' => 'ngoi-am-duong-ct',
        'q' => 'NAD-DELETED-001',
    ]));
    $searchResponse->assertOk()
        ->assertJsonMissing([
            'id' => $deletedProduct->ngoi_am_duong_ct_id,
        ]);

    // Detail should return 404 for deleted product
    $detailResponse = $this->getJson(route('admin.product-copy.detail', [
        'type' => 'ngoi-am-duong-ct',
        'id' => $deletedProduct->ngoi_am_duong_ct_id,
    ]));
    $detailResponse->assertNotFound();
});

