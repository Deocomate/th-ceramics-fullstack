<?php

use App\Domains\Catalog\Domain\PhuKienNgoiCategory;
use App\Domains\Catalog\Infrastructure\ProductWriter;
use App\Domains\Catalog\Infrastructure\PublicIdAllocator;

test('phu kien detail routes only render products from the matching category', function () {
    $boNoc = app(ProductWriter::class)->create('phu_kien_ngoi_ct', [
        'name' => 'Ngói bò nóc đúng',
        'category_type' => PhuKienNgoiCategory::TYPE_BO_NOC,
        'images' => [],
        'des' => ['Mô tả bò nóc'],
        'is_delete' => 0,
    ]);

    $chuVan = app(ProductWriter::class)->create('phu_kien_ngoi_ct', [
        'name' => 'Bò nóc chữ vạn đúng',
        'category_type' => PhuKienNgoiCategory::TYPE_CHU_VAN,
        'images' => ['assets/images/chu-van-1.webp'],
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
    $product = app(ProductWriter::class)->create('phu_kien_ngoi_ct', [
        'name' => 'Phụ kiện có giỏ',
        'category_type' => PhuKienNgoiCategory::TYPE_BO_NOC,
        'images' => ['assets/images/bo-noc.webp'],
        'is_delete' => 0,
    ]);

    $activeVariant = $product->variants()->create([
        'name' => 'Loại đang bán',
        'sku' => 'PKN-CART-001',
        'price' => 120000,
        'is_delete' => 0,
        'is_default' => false,
    ]);
    app(PublicIdAllocator::class)->variant($activeVariant->setRelation('product', $product));

    $hiddenVariant = $product->variants()->create([
        'name' => 'Loại đã ẩn',
        'sku' => 'PKN-CART-HIDDEN',
        'price' => 130000,
        'is_delete' => 1,
        'is_default' => false,
    ]);
    app(PublicIdAllocator::class)->variant($hiddenVariant->setRelation('product', $product));

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
    $product = app(ProductWriter::class)->create('phu_kien_ngoi_ct', [
        'name' => 'Phụ kiện URL cũ',
        'category_type' => PhuKienNgoiCategory::TYPE_CHU_VAN,
        'legacy_type' => PhuKienNgoiCategory::TYPE_CHU_VAN,
        'legacy_id' => 901,
        'images' => [],
        'is_delete' => 0,
    ]);

    $this->get(route('client.products.phu-kien-ngoi.detail', ['id' => 901, 'type' => 'chu_van']))
        ->assertRedirect(route('client.products.phu-kien-ngoi.bo-noc-chu-van.detail', $product->getKey()));
});
