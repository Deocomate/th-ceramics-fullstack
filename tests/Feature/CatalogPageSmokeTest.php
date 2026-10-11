<?php

use App\Domains\Catalog\Domain\PhuKienNgoiCategory;
use App\Domains\Catalog\Domain\ProductTypeRegistry;
use App\Domains\Catalog\Infrastructure\Models\Product;
use App\Domains\Catalog\Infrastructure\ProductWriter;
use App\Domains\Identity\Infrastructure\Models\User;
use Database\Seeders\ProductDetailSeeder;

test('accessory admin list and edit pages render canonical products in both categories', function () {
    $user = User::factory()->create();

    foreach ([
        PhuKienNgoiCategory::TYPE_BO_NOC => 'NBN-',
        PhuKienNgoiCategory::TYPE_CHU_VAN => 'BNCV-',
    ] as $category => $prefix) {
        $product = app(ProductWriter::class)->create('phu_kien_ngoi_ct', [
            'name' => 'Phụ kiện '.$category,
            'category_type' => $category,
            'images' => [],
            'is_delete' => false,
        ]);

        $this->actingAs($user)
            ->get(route('admin.phu-kien-ngoi-ct.index', ['category_type' => $category]))
            ->assertOk()
            ->assertSee('Phụ kiện '.$category)
            ->assertSee($prefix.$product->public_id);

        $this->actingAs($user)
            ->get(route('admin.phu-kien-ngoi-ct.edit', $product->public_id))
            ->assertOk()
            ->assertSee('Phụ kiện '.$category);
    }
});

test('lan can detail renders canonical related products without deleted products', function () {
    $product = app(ProductWriter::class)->create('lan_can_gom_su_ct', [
        'name' => 'Lan can đang xem',
        'images' => [],
        'is_delete' => false,
    ]);
    app(ProductWriter::class)->create('lan_can_gom_su_ct', [
        'name' => 'Lan can liên quan',
        'images' => [],
        'is_delete' => false,
    ]);
    app(ProductWriter::class)->create('lan_can_gom_su_ct', [
        'name' => 'Lan can đã ẩn',
        'images' => [],
        'is_delete' => true,
    ]);

    $this->get(route('client.products.lan-can-gom-su.detail', $product->public_id))
        ->assertOk()
        ->assertSee('Lan can đang xem')
        ->assertSee('Lan can liên quan')
        ->assertDontSee('Lan can đã ẩn');
});

test('every catalog product group renders its admin and customer pages', function () {
    $this->seed();
    $user = User::factory()->create();
    $customerIndexRoutes = [
        'ngoi_am_duong_ct' => 'client.products.ngoi-am-duong.index',
        'ngoi_hai_van_mieu_ct' => 'client.products.ngoi-hai-van-mieu.index',
        'ngoi_hai_co_ct' => 'client.products.ngoi-hai-van-mieu.index',
        'gach_hoa_thong_gio_ct' => 'client.products.gach-hoa-thong-gio.index',
        'gach_trang_tri_ct' => 'client.products.gach-trang-tri.index',
        'gach_co_bat_trang_ct' => 'client.products.gach-co-bat-trang.index',
        'linh_vat_phong_thuy_ct' => 'client.products.linh-vat-phong-thuy.index',
        'lan_can_gom_su_ct' => 'client.products.lan-can-gom-su.index',
        'den_vuon_gom_su_ct' => 'client.products.den-gom-su.index',
        'phu_kien_ngoi_ct' => 'client.products.phu-kien-ngoi.index',
    ];

    foreach (array_keys(ProductTypeRegistry::all()) as $index => $type) {
        $category = match ($type) {
            'gach_co_bat_trang_ct' => 'bat',
            'den_vuon_gom_su_ct' => 'den_gom',
            'phu_kien_ngoi_ct' => PhuKienNgoiCategory::TYPE_BO_NOC,
            default => null,
        };
        $name = 'Sản phẩm kiểm tra '.$index;
        $product = app(ProductWriter::class)->create($type, [
            'name' => $name,
            'code' => 'SMOKE-'.$index,
            'price' => 100000,
            'category_type' => $category,
            'images' => [],
            'is_delete' => false,
        ]);
        $adminPrefix = 'admin.'.str_replace('_', '-', $type);
        $adminQuery = $category === null ? [] : ['category_type' => $category];

        $this->actingAs($user)->get(route($adminPrefix.'.index', $adminQuery))->assertOk();
        $this->actingAs($user)->get(route($adminPrefix.'.create', $adminQuery))->assertOk();
        $this->actingAs($user)->get(route($adminPrefix.'.edit', $product->public_id))->assertOk();
        $indexResponse = $this->get(route($customerIndexRoutes[$type]));
        $this->assertSame(200, $indexResponse->status(), $type.' customer index');
        $this->get(route(ProductTypeRegistry::detailRoute($type, $category), $product->public_id))
            ->assertOk()
            ->assertSee($name);
    }

    foreach ([
        'admin.mau-sac-ngoi-am-duong-ct.index',
        'admin.mau-sac-ngoi-hai-co-ct.index',
        'admin.mau-sac-ngoi-hai-van-mieu-ct.index',
        'admin.phan-loai-den-vuon-gom-su-ct.index',
        'admin.phan-loai-lan-can-gom-su-ct.index',
        'admin.phan-loai-phu-kien-ngoi-ct.index',
    ] as $routeName) {
        $this->actingAs($user)->get(route($routeName))->assertOk();
    }
});

test('standalone product detail seeder fills the canonical catalog', function () {
    $this->seed(ProductDetailSeeder::class);

    foreach (array_keys(ProductTypeRegistry::all()) as $type) {
        expect(Product::where('type_key', $type)->exists())->toBeTrue();
    }
});
