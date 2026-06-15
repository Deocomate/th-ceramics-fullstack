<?php

use App\Models\LinhVatPhongThuyCt;

test('mini cart returns empty payload when session cart is empty', function () {
    $this->getJson(route('client.cart.mini'))
        ->assertSuccessful()
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('cart_count', 0)
        ->assertJsonPath('items', []);
});

test('mini cart returns items after adding to cart', function () {
    $product = LinhVatPhongThuyCt::query()->create([
        'name' => 'Linh vật mini cart',
        'code' => 'LV-MINI-001',
        'price' => 200000,
        'images' => ['assets/images/linh-vat.png'],
        'is_delete' => 0,
    ]);

    $this->postJson(route('client.cart.add'), [
        'product_type' => 'linh_vat_phong_thuy_ct',
        'product_id' => $product->linh_vat_phong_thuy_ct_id,
        'qty' => 2,
    ])->assertSuccessful()
        ->assertJsonPath('cart_count', 2)
        ->assertJsonPath('item.name', 'Linh vật mini cart');

    $this->getJson(route('client.cart.mini'))
        ->assertSuccessful()
        ->assertJsonPath('cart_count', 2)
        ->assertJsonPath('items.0.name', 'Linh vật mini cart')
        ->assertJsonPath('items.0.quantity', 2);
});

test('add to cart increments cart count in response', function () {
    $product = LinhVatPhongThuyCt::query()->create([
        'name' => 'Linh vật count',
        'code' => 'LV-COUNT-001',
        'price' => 150000,
        'images' => [],
        'is_delete' => 0,
    ]);

    $this->postJson(route('client.cart.add'), [
        'product_type' => 'linh_vat_phong_thuy_ct',
        'product_id' => $product->linh_vat_phong_thuy_ct_id,
        'qty' => 1,
    ])->assertSuccessful()
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('cart_count', 1);

    $this->postJson(route('client.cart.add'), [
        'product_type' => 'linh_vat_phong_thuy_ct',
        'product_id' => $product->linh_vat_phong_thuy_ct_id,
        'qty' => 3,
    ])->assertSuccessful()
        ->assertJsonPath('cart_count', 4);
});
