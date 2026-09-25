<?php

use App\Models\NgoiAmDuongCt;
use App\Models\PhuKienNgoiCt;
use App\Models\User;

function makePriorityProduct(string $code, string $name, bool $hidden = false): NgoiAmDuongCt
{
    return NgoiAmDuongCt::query()->create([
        'code' => $code,
        'name' => $name,
        'images' => [],
        'price' => 1000,
        'is_delete' => $hidden,
    ]);
}

test('admin can reorder active products and the order is persisted', function () {
    $this->actingAs(User::factory()->create());
    $first = makePriorityProduct('PRIORITY-001', 'Sản phẩm A');
    $second = makePriorityProduct('PRIORITY-002', 'Sản phẩm B');
    $third = makePriorityProduct('PRIORITY-003', 'Sản phẩm C');

    $this->putJson(route('admin.products.priority.update', 'ngoi-am-duong-ct'), [
        'ids' => [$third->getKey(), $first->getKey(), $second->getKey()],
    ])->assertOk();

    expect(NgoiAmDuongCt::query()->orderedByPriority()->pluck('ngoi_am_duong_ct_id')->map(fn ($id) => (int) $id)->all())
        ->toBe([$third->getKey(), $first->getKey(), $second->getKey()]);
});

test('homepage product sections use the persisted priority order', function () {
    makePriorityProduct('PRIORITY-HOME-001', 'Ưu tiên thấp hơn');
    makePriorityProduct('PRIORITY-HOME-002', 'Ưu tiên cao hơn');

    $response = $this->get(route('client.home'))->assertOk();
    $content = $response->getContent();

    expect(strpos($content, 'Ưu tiên cao hơn'))
        ->toBeLessThan(strpos($content, 'Ưu tiên thấp hơn'));
});

test('reorder rejects missing, hidden, duplicate and cross-category product IDs without partial updates', function () {
    $this->actingAs(User::factory()->create());
    $first = makePriorityProduct('PRIORITY-004', 'Sản phẩm A');
    $hidden = makePriorityProduct('PRIORITY-005', 'Sản phẩm ẩn', true);
    $before = $first->priority;

    $this->putJson(route('admin.products.priority.update', 'ngoi-am-duong-ct'), [
        'ids' => [$first->getKey(), $hidden->getKey()],
    ])->assertUnprocessable();
    $this->putJson(route('admin.products.priority.update', 'ngoi-am-duong-ct'), [
        'ids' => [$first->getKey(), $first->getKey()],
    ])->assertUnprocessable();
    $this->putJson(route('admin.products.priority.update', 'ngoi-am-duong-ct'), [
        'ids' => [$first->getKey(), 999999],
    ])->assertUnprocessable();

    $boNoc = PhuKienNgoiCt::query()->create(['name' => 'Bò nóc', 'category_type' => PhuKienNgoiCt::TYPE_BO_NOC]);
    $chuVan = PhuKienNgoiCt::query()->create(['name' => 'Chữ vạn', 'category_type' => PhuKienNgoiCt::TYPE_CHU_VAN]);
    $this->putJson(route('admin.products.priority.update', 'phu-kien-ngoi-ct'), [
        'category_type' => PhuKienNgoiCt::TYPE_BO_NOC,
        'ids' => [$boNoc->getKey(), $chuVan->getKey()],
    ])->assertUnprocessable();

    expect($first->fresh()->priority)->toBe($before);
});

test('product list pages expose drag sorting only for active scoped lists', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('admin.products.priority.update', 'ngoi-am-duong-ct'))
        ->assertStatus(405);

    $this->get(route('admin.ngoi-am-duong-ct.index'))
        ->assertOk()
        ->assertSee('data-admin-product-sortable', false)
        ->assertSee('data-can-reorder="true"', false)
        ->assertSee('assets/js/admin-select.js', false);

    $this->get(route('admin.gach-co-bat-trang-ct.index', ['category_type' => 'all']))
        ->assertOk()
        ->assertSee('data-can-reorder="false"', false);
});

test('guest cannot save product priorities', function () {
    $this->putJson(route('admin.products.priority.update', 'ngoi-am-duong-ct'), [
        'ids' => [1],
    ])->assertUnauthorized();
});
