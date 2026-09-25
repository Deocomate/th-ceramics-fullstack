<?php

use App\Models\NgoiAmDuongCt;
use App\Models\PhuKienNgoiCt;
use App\Models\User;

function createBulkRenameNgoiAmDuongProduct(string $code, string $name, bool $deleted = false): NgoiAmDuongCt
{
    return NgoiAmDuongCt::query()->create([
        'code' => $code,
        'name' => $name,
        'images' => [],
        'price' => 1000,
        'is_delete' => $deleted,
    ]);
}

test('admin renames selected products in submitted display order including hidden products', function () {
    $this->actingAs(User::factory()->create());
    $first = createBulkRenameNgoiAmDuongProduct('BULK-001', 'Cũ 1');
    $second = createBulkRenameNgoiAmDuongProduct('BULK-002', 'Cũ 2', true);

    $this->post(route('admin.products.bulk-rename', ['type' => 'ngoi-am-duong-ct']), [
        'ids' => [$second->ngoi_am_duong_ct_id, $first->ngoi_am_duong_ct_id],
        'base_name' => 'Ngói âm dương sen tỏa',
    ])->assertRedirect()->assertSessionHas('success', 'Đã đổi tên 2 sản phẩm thành công.');

    expect($second->fresh()->name)->toBe('Ngói âm dương sen tỏa 1')
        ->and($first->fresh()->name)->toBe('Ngói âm dương sen tỏa 2')
        ->and($first->fresh()->code)->toBe('BULK-001');
});

test('bulk rename rejects products outside the selected accessory category without partial changes', function () {
    $this->actingAs(User::factory()->create());
    $bocNoc = PhuKienNgoiCt::query()->create([
        'name' => 'Bò nóc',
        'category_type' => PhuKienNgoiCt::TYPE_BO_NOC,
    ]);
    $chuVan = PhuKienNgoiCt::query()->create([
        'name' => 'Chữ vạn',
        'category_type' => PhuKienNgoiCt::TYPE_CHU_VAN,
    ]);

    $this->from(route('admin.phu-kien-ngoi-ct.index', ['category_type' => PhuKienNgoiCt::TYPE_BO_NOC]))
        ->post(route('admin.products.bulk-rename', ['type' => 'phu-kien-ngoi-ct']), [
            'ids' => [$bocNoc->phu_kien_ngoi_ct_id, $chuVan->phu_kien_ngoi_ct_id],
            'base_name' => 'Phụ kiện',
            'category_type' => PhuKienNgoiCt::TYPE_BO_NOC,
        ])->assertRedirect()->assertSessionHasErrors('base_name');

    expect($bocNoc->fresh()->name)->toBe('Bò nóc')
        ->and($chuVan->fresh()->name)->toBe('Chữ vạn');
});

test('bulk rename rejects generated names longer than 255 characters without partial changes', function () {
    $this->actingAs(User::factory()->create());
    $product = createBulkRenameNgoiAmDuongProduct('BULK-003', 'Tên cũ');

    $this->post(route('admin.products.bulk-rename', ['type' => 'ngoi-am-duong-ct']), [
        'ids' => [$product->ngoi_am_duong_ct_id],
        'base_name' => str_repeat('a', 255),
    ])->assertRedirect()->assertSessionHasErrors('base_name');

    expect($product->fresh()->name)->toBe('Tên cũ');
});

test('bulk rename rejects a missing selected ID without partial changes', function () {
    $this->actingAs(User::factory()->create());
    $product = createBulkRenameNgoiAmDuongProduct('BULK-004', 'Tên cũ');

    $this->post(route('admin.products.bulk-rename', ['type' => 'ngoi-am-duong-ct']), [
        'ids' => [$product->ngoi_am_duong_ct_id, 999999],
        'base_name' => 'Tên mới',
    ])->assertRedirect()->assertSessionHasErrors('base_name');

    expect($product->fresh()->name)->toBe('Tên cũ');
});

test('bulk rename rejects an empty name and duplicate IDs', function () {
    $this->actingAs(User::factory()->create());
    $product = createBulkRenameNgoiAmDuongProduct('BULK-005', 'Tên cũ');

    $this->post(route('admin.products.bulk-rename', ['type' => 'ngoi-am-duong-ct']), [
        'ids' => [$product->ngoi_am_duong_ct_id],
        'base_name' => '   ',
    ])->assertRedirect()->assertSessionHasErrors('base_name');

    $this->post(route('admin.products.bulk-rename', ['type' => 'ngoi-am-duong-ct']), [
        'ids' => [$product->ngoi_am_duong_ct_id, $product->ngoi_am_duong_ct_id],
        'base_name' => 'Tên mới',
    ])->assertRedirect()->assertSessionHasErrors('ids.1');

    expect($product->fresh()->name)->toBe('Tên cũ');
});

test('guest cannot use bulk rename', function () {
    $this->post(route('admin.products.bulk-rename', ['type' => 'ngoi-am-duong-ct']), [
        'ids' => [1],
        'base_name' => 'Tên mới',
    ])->assertRedirect(route('admin.auth.login'));
});

test('all product detail lists render bulk selection controls', function () {
    $this->actingAs(User::factory()->create());

    $routes = [
        'admin.ngoi-am-duong-ct.index',
        'admin.ngoi-hai-co-ct.index',
        'admin.ngoi-hai-van-mieu-ct.index',
        'admin.gach-hoa-thong-gio-ct.index',
        'admin.gach-trang-tri-ct.index',
        'admin.gach-co-bat-trang-ct.index',
        'admin.linh-vat-phong-thuy-ct.index',
        'admin.lan-can-gom-su-ct.index',
        'admin.den-vuon-gom-su-ct.index',
    ];

    foreach ($routes as $route) {
        $this->get(route($route))
            ->assertOk()
            ->assertSee('data-bulk-rename-select-all', false)
            ->assertSee('bulk-rename-product-checkbox', false);
    }

    foreach ([PhuKienNgoiCt::TYPE_BO_NOC, PhuKienNgoiCt::TYPE_CHU_VAN] as $categoryType) {
        $this->get(route('admin.phu-kien-ngoi-ct.index', ['category_type' => $categoryType]))
            ->assertOk()
            ->assertSee('data-bulk-rename-select-all', false)
            ->assertSee('bulk-rename-product-checkbox', false);
    }
});
