<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductVariant;
use App\Domains\Catalog\PublicIdAllocator;
use App\Domains\Catalog\Services\CatalogQueryService;
use Illuminate\Support\Facades\DB;

it('preserves imported IDs and allocates new public IDs within each product type', function () {
    $allocator = app(PublicIdAllocator::class);
    $imported = Product::create(['type_key' => 'ngoi_am_duong_ct', 'name' => 'Old tile']);
    $new = Product::create(['type_key' => 'ngoi_am_duong_ct', 'name' => 'New tile']);
    $otherType = Product::create(['type_key' => 'gach_trang_tri_ct', 'name' => 'Brick']);

    expect($allocator->product($imported, 42))->toBe(42);
    expect($allocator->product($imported, 42))->toBe(42);
    expect($allocator->product($new))->toBe(43);
    expect($allocator->product($otherType))->toBe(1);
    expect(DB::table('product_public_ids')->count())->toBe(3);

    $importedVariant = ProductVariant::create(['product_id' => $imported->id, 'sku' => 'PUBLIC-OLD', 'price' => 100]);
    $newVariant = ProductVariant::create(['product_id' => $new->id, 'sku' => 'PUBLIC-NEW', 'price' => 200]);
    expect($allocator->variant($importedVariant, 91))->toBe(91);
    expect($allocator->variant($newVariant))->toBe(92);
    expect(DB::table('variant_public_ids')->count())->toBe(2);
});

it('reports a public ID collision without assigning it to the wrong product', function () {
    $allocator = app(PublicIdAllocator::class);
    $first = Product::create(['type_key' => 'ngoi_am_duong_ct', 'name' => 'First']);
    $second = Product::create(['type_key' => 'ngoi_am_duong_ct', 'name' => 'Second']);

    $allocator->product($first, 7);
    expect(fn () => $allocator->product($second, 7))->toThrow(DomainException::class);
    expect(DB::table('product_public_ids')->count())->toBe(1);
});

it('reserves historical variant IDs before allocating a default variant', function () {
    $allocator = app(PublicIdAllocator::class);
    $product = Product::create(['type_key' => 'ngoi_hai_van_mieu_ct', 'name' => 'Tile with color']);

    $historicalVariant = ProductVariant::create(['product_id' => $product->id, 'sku' => 'COLOR-50', 'price' => 200]);
    $allocatedHistorical = $allocator->variant($historicalVariant, 50);
    expect($allocatedHistorical)->toBe(50);

    $allocator->reserveVariantFloor('ngoi_hai_van_mieu_ct', 100);

    $nextVariant = ProductVariant::create(['product_id' => $product->id, 'sku' => 'COLOR-AUTO', 'price' => 300]);
    $allocatedNext = $allocator->variant($nextVariant);
    expect($allocatedNext)->toBeGreaterThanOrEqual(100);
    expect(DB::table('variant_public_ids')->where('type_key', 'ngoi_hai_van_mieu_ct')->where('public_id', 50)->exists())->toBeTrue();
});

it('resolves a product by public ID even when it matches another database ID', function () {
    $allocator = app(PublicIdAllocator::class);
    $first = Product::create(['type_key' => 'ngoi_am_duong_ct', 'name' => 'First']);
    $second = Product::create(['type_key' => 'ngoi_am_duong_ct', 'name' => 'Second']);
    $allocator->product($first, 2);
    $allocator->product($second, 1);

    expect(app(CatalogQueryService::class)->find('ngoi_am_duong_ct', 1)->id)->toBe($second->id);
    expect(app(CatalogQueryService::class)->find('ngoi_am_duong_ct', 2)->id)->toBe($first->id);
});
