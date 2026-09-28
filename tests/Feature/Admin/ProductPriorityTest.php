<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\ProductWriter;
use App\Domains\Catalog\PublicIdAllocator;
use App\Domains\Identity\Models\User;

function makePriorityProduct(string $code, string $name, bool $hidden = false): Product
{
    return app(ProductWriter::class)->create('ngoi_am_duong_ct', [
        'code' => $code,
        'name' => $name,
        'images' => [],
        'price' => 1000,
        'is_delete' => $hidden ? 1 : 0,
    ]);
}

test('admin can reorder active products and the order is persisted', function () {
    $this->actingAs(User::factory()->create());
    $first = makePriorityProduct('PRIORITY-001', 'Sản phẩm A');
    $second = makePriorityProduct('PRIORITY-002', 'Sản phẩm B');
    $third = makePriorityProduct('PRIORITY-003', 'Sản phẩm C');

    $this->putJson(route('admin.products.priority.update', 'ngoi-am-duong-ct'), [
        'ids' => [$third->public_id, $first->public_id, $second->public_id],
    ])->assertOk();

    expect(Product::where('type_key', 'ngoi_am_duong_ct')->orderedByPriority()->get()->map(fn ($p) => (int) $p->public_id)->all())
        ->toBe([$third->public_id, $first->public_id, $second->public_id]);
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
        'ids' => [$first->public_id, $hidden->public_id],
    ])->assertUnprocessable();
    $this->putJson(route('admin.products.priority.update', 'ngoi-am-duong-ct'), [
        'ids' => [$first->public_id, $first->public_id],
    ])->assertUnprocessable();
    $this->putJson(route('admin.products.priority.update', 'ngoi-am-duong-ct'), [
        'ids' => [$first->public_id, 999999],
    ])->assertUnprocessable();

    $boNoc = app(ProductWriter::class)->create('phu_kien_ngoi_ct', ['name' => 'Bò nóc', 'category_type' => 'bo_noc']);
    $chuVan = app(ProductWriter::class)->create('phu_kien_ngoi_ct', ['name' => 'Chữ vạn', 'category_type' => 'chu_van']);
    $this->putJson(route('admin.products.priority.update', 'phu-kien-ngoi-ct'), [
        'category_type' => 'bo_noc',
        'ids' => [$boNoc->public_id, $chuVan->public_id],
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

test('reorder uses public IDs when they collide with database IDs', function () {
    $this->actingAs(User::factory()->create());
    $first = Product::create(['type_key' => 'ngoi_am_duong_ct', 'name' => 'First']);
    $second = Product::create(['type_key' => 'ngoi_am_duong_ct', 'name' => 'Second']);
    app(PublicIdAllocator::class)->product($first, 2);
    app(PublicIdAllocator::class)->product($second, 1);

    $this->putJson(route('admin.products.priority.update', 'ngoi-am-duong-ct'), [
        'ids' => [1, 2],
    ])->assertOk();

    expect($second->fresh()->priority)->toBeGreaterThan($first->fresh()->priority);
});
