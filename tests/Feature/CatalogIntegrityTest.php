<?php

use App\Domains\Catalog\Infrastructure\Models\Product;
use App\Domains\Catalog\Infrastructure\Models\ProductDisplayOption;
use App\Domains\Catalog\Infrastructure\ProductWriter;
use App\Domains\Catalog\Infrastructure\PublicIdAllocator;
use App\Domains\Catalog\Infrastructure\Services\CatalogQueryService;
use App\Domains\Identity\Infrastructure\Models\User;
use Illuminate\Validation\ValidationException;

test('variant admin actions are scoped to the public ID and product group', function () {
    $den = app(ProductWriter::class)->create('den_vuon_gom_su_ct', ['name' => 'Đèn']);
    $lanCan = app(ProductWriter::class)->create('lan_can_gom_su_ct', ['name' => 'Lan can']);
    $denVariant = app(ProductWriter::class)->saveVariant($den, [
        'name' => 'Đèn loại một', 'sku' => 'DEN-ONE', 'price' => 100000,
    ]);
    $lanCanVariant = app(ProductWriter::class)->saveVariant($lanCan, [
        'name' => 'Lan can loại một', 'sku' => 'LAN-ONE', 'price' => 200000,
    ]);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.phan-loai-den-vuon-gom-su-ct.update', $lanCanVariant->id), [
            'name' => 'Sai nhóm', 'code' => 'WRONG-TYPE', 'price' => 1,
        ])->assertNotFound();

    $this->actingAs(User::factory()->create())
        ->put(route('admin.phan-loai-den-vuon-gom-su-ct.update', $denVariant->public_id), [
            'name' => 'Đèn đã sửa', 'code' => 'DEN-UPDATED', 'price' => 110000,
        ])->assertRedirect();

    expect($denVariant->fresh()->sku)->toBe('DEN-UPDATED');
    expect($lanCanVariant->fresh()->sku)->toBe('LAN-ONE');
});

test('variant exposes its parent public ID separately from its own public ID', function () {
    app(ProductWriter::class)->create('den_vuon_gom_su_ct', ['name' => 'Đèn khác']);
    $product = app(ProductWriter::class)->create('den_vuon_gom_su_ct', ['name' => 'Đèn có phân loại']);
    $variant = app(ProductWriter::class)->saveVariant($product, [
        'name' => 'Phân loại A', 'sku' => 'DEN-PARENT-ID', 'price' => 100000,
    ]);

    expect($variant->den_vuon_gom_su_ct_id)->toBe($product->public_id);
    expect($variant->phan_loai_den_vuon_gom_su_ct_id)->toBe($variant->public_id);
    expect($variant->public_id)->not->toBe($product->public_id);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.phan-loai-den-vuon-gom-su-ct.index'))
        ->assertOk()
        ->assertSee('data-pid="'.$product->public_id.'"', false);
});

test('cart uses variant public IDs when database IDs differ', function () {
    $product = app(ProductWriter::class)->create('lan_can_gom_su_ct', ['name' => 'Lan can có màu']);
    $first = $product->variants()->create(['name' => 'Màu một', 'sku' => 'COLOR-ONE', 'price' => 100000]);
    $second = $product->variants()->create(['name' => 'Màu hai', 'sku' => 'COLOR-TWO', 'price' => 200000]);
    app(PublicIdAllocator::class)->variant($first->setRelation('product', $product), 2);
    app(PublicIdAllocator::class)->variant($second->setRelation('product', $product), 1);

    expect(app(CatalogQueryService::class)->cartDetails('lan_can_gom_su_ct', $product->public_id, 1)['sku'])
        ->toBe('COLOR-TWO');
});

test('hidden products cannot be added to an existing cart row or requested as options', function () {
    $product = app(ProductWriter::class)->create('gach_trang_tri_ct', [
        'name' => 'Gạch đang bán', 'code' => 'CART-VISIBLE', 'price' => 100000,
    ]);
    $payload = [
        'product_type' => 'gach_trang_tri_ct',
        'product_id' => $product->public_id,
        'qty' => 1,
    ];

    $this->postJson(route('client.cart.add'), $payload)->assertSuccessful();
    app(ProductWriter::class)->setHidden($product, true);

    $this->postJson(route('client.cart.add'), $payload)->assertUnprocessable();
    $this->getJson(route('client.cart.product-options', [
        'product_type' => 'gach_trang_tri_ct',
        'product_id' => $product->public_id,
    ]))->assertNotFound();
});

test('products without a selling price require contact instead of entering the cart', function () {
    $product = app(ProductWriter::class)->create('gach_trang_tri_ct', [
        'name' => 'Gạch cần báo giá', 'code' => 'CONTACT-PRICE', 'price' => 0,
    ]);

    $this->postJson(route('client.cart.add'), [
        'product_type' => 'gach_trang_tri_ct',
        'product_id' => $product->public_id,
        'qty' => 1,
    ])->assertUnprocessable()
        ->assertJsonPath('status', 'error');
});

test('writer rejects duplicate SKUs before creating products or variants', function () {
    $writer = app(ProductWriter::class);
    $writer->create('gach_trang_tri_ct', ['name' => 'First', 'code' => 'SKU-UNIQUE']);

    expect(fn () => $writer->create('gach_trang_tri_ct', ['name' => 'Second', 'code' => 'SKU-UNIQUE']))
        ->toThrow(ValidationException::class);
    expect(Product::where('name', 'Second')->exists())->toBeFalse();

    $product = $writer->create('lan_can_gom_su_ct', ['name' => 'Third']);
    expect(fn () => $writer->saveVariant($product, ['code' => 'SKU-UNIQUE']))
        ->toThrow(ValidationException::class);
});

test('new display options receive an ID distinct from imported colors', function () {
    $imported = ProductDisplayOption::create([
        'type_key' => 'ngoi_am_duong_ct',
        'legacy_id' => 2,
        'name' => 'Màu nhập',
    ]);
    $created = app(ProductWriter::class)->saveDisplayOption('ngoi_am_duong_ct', [
        'name' => 'Màu mới',
    ]);

    expect($created->public_id)->toBeGreaterThan($imported->public_id);
    expect(ProductDisplayOption::findByPublicId('ngoi_am_duong_ct', $created->public_id)->id)->toBe($created->id);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.mau-sac-ngoi-am-duong-ct.update', $created->public_id), [
            'name' => 'Màu mới đã sửa',
        ])->assertRedirect();

    expect($created->fresh()->name)->toBe('Màu mới đã sửa');
    expect($imported->fresh()->name)->toBe('Màu nhập');
});
