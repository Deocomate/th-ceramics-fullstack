<?php

use App\Models\LanCanGomSuCt;
use App\Models\LinhVatPhongThuyCt;
use App\Models\MauSacNgoiAmDuongCt;
use App\Models\NgoiAmDuongCt;
use App\Models\PhanLoaiLanCanGomSuCt;

test('product options returns global color palette for ngoi am duong', function () {
    $product = NgoiAmDuongCt::query()->create([
        'name' => 'Ngói âm dương test',
        'code' => 'NAD-001',
        'price' => 320000,
        'images' => ['assets/images/ngoi-01.jpg'],
        'is_delete' => 0,
    ]);

    $color = MauSacNgoiAmDuongCt::query()->create([
        'name' => 'Đỏ cờ',
        'image' => 'assets/images/red.png',
    ]);

    $this->getJson(route('client.cart.product-options', [
        'product_type' => 'ngoi_am_duong_ct',
        'product_id' => $product->ngoi_am_duong_ct_id,
    ]))
        ->assertSuccessful()
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('data.requires_variant', false)
        ->assertJsonPath('data.variant_label', 'Màu sắc')
        ->assertJsonPath('data.variants.0.id', $color->mau_sac_ngoi_am_duong_ct_id)
        ->assertJsonPath('data.unit_price', 320000);
});

test('product options returns variants for lan can gom su', function () {
    $product = LanCanGomSuCt::query()->create([
        'name' => 'Lan can test',
        'images' => ['assets/images/lan-can.png'],
        'is_delete' => 0,
    ]);

    $variant = PhanLoaiLanCanGomSuCt::query()->create([
        'lan_can_gom_su_ct_id' => $product->lan_can_gom_su_ct_id,
        'name' => 'Phân loại A',
        'code' => 'LC-001',
        'price' => 250000,
        'is_delete' => 0,
    ]);

    $this->getJson(route('client.cart.product-options', [
        'product_type' => 'lan_can_gom_su_ct',
        'product_id' => $product->lan_can_gom_su_ct_id,
    ]))
        ->assertSuccessful()
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('data.product_type', 'lan_can_gom_su_ct')
        ->assertJsonPath('data.requires_variant', true)
        ->assertJsonPath('data.variants.0.id', $variant->phan_loai_lan_can_gom_su_ct_id);
});

test('product options rejects invalid product type', function () {
    $this->getJson(route('client.cart.product-options', [
        'product_type' => 'invalid_type',
        'product_id' => 1,
    ]))->assertUnprocessable();
});

test('product options returns simple product without variants', function () {
    $product = LinhVatPhongThuyCt::query()->create([
        'name' => 'Linh vật test',
        'code' => 'LV-001',
        'price' => 180000,
        'images' => ['assets/images/linh-vat.png'],
        'is_delete' => 0,
    ]);

    $this->getJson(route('client.cart.product-options', [
        'product_type' => 'linh_vat_phong_thuy_ct',
        'product_id' => $product->linh_vat_phong_thuy_ct_id,
    ]))
        ->assertSuccessful()
        ->assertJsonPath('data.requires_variant', false)
        ->assertJsonPath('data.unit_price', 180000)
        ->assertJsonPath('data.variants', []);
});
