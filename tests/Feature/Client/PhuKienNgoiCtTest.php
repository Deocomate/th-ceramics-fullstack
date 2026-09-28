<?php

use App\Models\PhanLoaiPhuKienNgoiCt;
use App\Models\PhuKienNgoiCt;
use App\Services\ProductBackfillService;

test('phu kien detail routes only render products from the matching category', function () {
    $boNoc = PhuKienNgoiCt::query()->create([
        'name' => 'Ngói bò nóc đúng',
        'category_type' => PhuKienNgoiCt::TYPE_BO_NOC,
        'images' => [],
        'des' => ['Mô tả bò nóc'],
        'is_delete' => 0,
    ]);

    $chuVan = PhuKienNgoiCt::query()->create([
        'name' => 'Bò nóc chữ vạn đúng',
        'category_type' => PhuKienNgoiCt::TYPE_CHU_VAN,
        'images' => ['assets/images/chu-van-1.png'],
        'des' => ['Mô tả chữ vạn'],
        'is_delete' => 0,
    ]);

    $this->get(route('client.products.phu-kien-ngoi.ngoi-bo-noc.detail', $boNoc->phu_kien_ngoi_ct_id))
        ->assertOk()
        ->assertSee('Ngói bò nóc đúng');

    $this->get(route('client.products.phu-kien-ngoi.ngoi-bo-noc.detail', $chuVan->phu_kien_ngoi_ct_id))
        ->assertNotFound();
});

test('cart accepts active phu kien variants and rejects inactive variants', function () {
    $product = PhuKienNgoiCt::query()->create([
        'name' => 'Phụ kiện có giỏ',
        'category_type' => PhuKienNgoiCt::TYPE_BO_NOC,
        'images' => ['assets/images/bo-noc.png'],
        'is_delete' => 0,
    ]);

    $activeVariant = PhanLoaiPhuKienNgoiCt::query()->create([
        'phu_kien_ngoi_ct_id' => $product->phu_kien_ngoi_ct_id,
        'name' => 'Loại đang bán',
        'code' => 'PKN-CART-001',
        'price' => 120000,
        'is_delete' => 0,
    ]);

    $hiddenVariant = PhanLoaiPhuKienNgoiCt::query()->create([
        'phu_kien_ngoi_ct_id' => $product->phu_kien_ngoi_ct_id,
        'name' => 'Loại đã ẩn',
        'code' => 'PKN-CART-HIDDEN',
        'price' => 130000,
        'is_delete' => 1,
    ]);

    $this->postJson(route('client.cart.add'), [
        'product_type' => 'phu_kien_ngoi_ct',
        'product_id' => $product->phu_kien_ngoi_ct_id,
        'variant_id' => $activeVariant->phan_loai_phu_kien_ngoi_ct_id,
        'qty' => 2,
    ])->assertSuccessful()
        ->assertJsonPath('status', 'success');

    $this->postJson(route('client.cart.add'), [
        'product_type' => 'phu_kien_ngoi_ct',
        'product_id' => $product->phu_kien_ngoi_ct_id,
        'variant_id' => $hiddenVariant->phan_loai_phu_kien_ngoi_ct_id,
        'qty' => 1,
    ])->assertUnprocessable()
        ->assertJsonPath('status', 'error');
});

test('legacy accessory URL resolves from the unified catalog', function () {
    $product = PhuKienNgoiCt::query()->create([
        'name' => 'Phụ kiện URL cũ',
        'category_type' => PhuKienNgoiCt::TYPE_CHU_VAN,
        'legacy_type' => PhuKienNgoiCt::TYPE_CHU_VAN,
        'legacy_id' => 901,
        'images' => [],
        'is_delete' => 0,
    ]);
    app(ProductBackfillService::class)->backfill();
    config()->set('product_catalog.read_unified', true);

    $this->get(route('client.products.phu-kien-ngoi.detail', ['id' => 901, 'type' => 'chu_van']))
        ->assertRedirect(route('client.products.phu-kien-ngoi.bo-noc-chu-van.detail', $product->getKey()));
});
