<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductVariant;
use App\Domains\Catalog\PublicIdAllocator;
use App\Services\ProductBackfillService;
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
    $first = DB::table('ngoi_hai_van_mieu_ct')->insertGetId([
        'name' => 'Tile without color', 'images' => '[]', 'price' => 100,
        'mau_sac_id' => 0, 'created_at' => now(), 'updated_at' => now(),
    ]);
    $second = DB::table('ngoi_hai_van_mieu_ct')->insertGetId([
        'name' => 'Tile with color', 'images' => '[]', 'price' => 200,
        'mau_sac_id' => 0, 'created_at' => now(), 'updated_at' => now(),
    ]);
    $historicalVariant = DB::table('mau_sac_ngoi_hai_van_mieu_ct')->insertGetId([
        'ngoi_hai_van_mieu_ct_id' => $second,
        'name' => 'Red', 'image' => 'assets/images/sample.jpg', 'code' => 'PUBLIC-COLOR-001',
        'price' => 300, 'created_at' => now(), 'updated_at' => now(),
    ]);

    app(ProductBackfillService::class)->backfill();

    $firstProduct = DB::table('product_public_ids')->where('type_key', 'ngoi_hai_van_mieu_ct')
        ->where('public_id', $first)->value('product_id');
    $defaultVariant = DB::table('product_variants')->where('product_id', $firstProduct)->where('is_default', true)->value('id');
    expect((int) DB::table('variant_public_ids')->where('product_variant_id', $defaultVariant)->value('public_id'))
        ->toBeGreaterThan($historicalVariant);
    expect(DB::table('variant_public_ids')->where('type_key', 'ngoi_hai_van_mieu_ct')
        ->where('public_id', $historicalVariant)->exists())->toBeTrue();
});
