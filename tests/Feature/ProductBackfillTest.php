<?php

use App\Models\NgoiAmDuongCt;
use App\Models\NgoiHaiCoCt;
use App\Models\MauSacNgoiHaiCoCt;
use App\Models\Product;
use App\Services\ProductBackfillService;
use App\Services\NgoiAmDuongCtService;
use App\Services\UnifiedProductCatalog;
use App\Services\CartService;
use App\Services\ProductCartOptionsService;
use Illuminate\Support\Facades\DB;

it('backfills direct products, variants, media and updates without duplicates', function () {
    $legacy = NgoiAmDuongCt::query()->create([
        'code' => 'BACKFILL-001',
        'name' => 'Ngói mẫu',
        'images' => ['assets/images/sample.jpg', ['type' => 'video', 'url' => 'https://youtu.be/abcd1234']],
        'price' => 25000,
        'is_delete' => false,
    ]);
    $service = app(ProductBackfillService::class);
    $service->backfill();
    $service->backfill();

    $product = Product::query()->with(['variants', 'media'])->firstOrFail();
    expect($product->name)->toBe('Ngói mẫu');
    expect($product->variants)->toHaveCount(1);
    expect($product->variants->first()->sku)->toBe('BACKFILL-001');
    expect($product->variants->first()->price)->toBe(25000);
    expect($product->media)->toHaveCount(2);
    expect($product->media->first()->is_cover)->toBeTrue();
    expect(DB::table('product_legacy_ids')->where('source_id', $legacy->getKey())->count())->toBe(1);

    config()->set('product_catalog.shadow_write', true);
    $legacy->update(['name' => 'Ngói đã sửa', 'is_delete' => true]);
    expect($product->fresh()->name)->toBe('Ngói đã sửa');
    expect($product->fresh()->is_delete)->toBeTrue();
    expect($service->verify()['missing']['ngoi_am_duong_ct'])->toBe(0);
    expect($service->verify()['mismatched']['ngoi_am_duong_ct'])->toBe(0);

    DB::table('products')->where('id', $product->id)->update(['name' => 'Sai lệch']);
    expect($service->verify()['examples']['ngoi_am_duong_ct'][0]['issues'])->toContain('name');
    $service->backfill();
    expect($service->verify()['mismatched']['ngoi_am_duong_ct'])->toBe(0);

    config()->set('product_catalog.read_unified', true);
    $projected = app(NgoiAmDuongCtService::class)->findById((int) $legacy->getKey());
    expect($projected->name)->toBe('Ngói đã sửa');
    expect($projected->code)->toBe('BACKFILL-001');
    expect($projected->images)->toHaveCount(2);
    expect(app(UnifiedProductCatalog::class)->paginate('ngoi_am_duong_ct', ['search' => 'BACKFILL-001'])->total())->toBe(0);
    expect(app(UnifiedProductCatalog::class)->paginate('ngoi_am_duong_ct', ['search' => 'Ngói'])->total())->toBe(0);
});

it('filters and paginates active unified products in the database', function () {
    foreach (range(1, 12) as $index) {
        NgoiAmDuongCt::query()->create([
            'code' => sprintf('PAGINATE-%03d', $index),
            'name' => 'Ngói '.str_pad((string) $index, 2, '0', STR_PAD_LEFT),
            'images' => [],
            'price' => 1000 + $index,
        ]);
    }
    app(ProductBackfillService::class)->backfill();
    $catalog = app(UnifiedProductCatalog::class);
    expect($catalog->paginate('ngoi_am_duong_ct', [], 8)->total())->toBe(12);
    expect($catalog->paginate('ngoi_am_duong_ct', ['search' => 'PAGINATE-012'], 8)->total())->toBe(1);
});

it('resolves legacy cart identifiers through unified variants', function () {
    $parent = NgoiHaiCoCt::query()->create(['name' => 'Ngói hài cổ', 'images' => []]);
    $variant = MauSacNgoiHaiCoCt::query()->create([
        'ngoi_hai_co_ct_id' => $parent->getKey(),
        'name' => 'Đỏ', 'image' => 'assets/images/sample.jpg',
        'code' => 'CART-LEGACY-001', 'price' => 32000,
    ]);
    app(ProductBackfillService::class)->backfill();
    config()->set('product_catalog.read_unified', true);

    $details = app(CartService::class)->getProductDetails('ngoi_hai_co_ct', $parent->getKey(), $variant->getKey());
    expect($details['sku'])->toBe('CART-LEGACY-001');
    expect($details['price'])->toBe(32000);
    expect($details['variant_name'])->toBe('Đỏ');
    $options = app(ProductCartOptionsService::class)->getOptions('ngoi_hai_co_ct', $parent->getKey());
    expect($options['requires_variant'])->toBeTrue();
    expect($options['default_variant_id'])->toBe($variant->getKey());

    config()->set('product_catalog.shadow_write', true);
    $variant->update(['price' => 35000]);
    expect(app(CartService::class)->getProductDetails('ngoi_hai_co_ct', $parent->getKey(), $variant->getKey())['price'])->toBe(35000);
});
