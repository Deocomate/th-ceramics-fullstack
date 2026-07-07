<?php

use App\Models\LinhVatPhongThuy;
use App\Models\LinhVatPhongThuyCt;
use App\Models\TrangChu;
use App\Services\TrangChuService;
use Illuminate\Support\Facades\Cache;

function ensureTrangChuRecord(): TrangChu
{
    return TrangChu::query()->first() ?? TrangChu::query()->create([
        'banner' => [],
        'khach_hang_doi_tac' => [],
        'loi_tri_an' => [],
        'loi_tri_an_anh' => '',
        've_chung_toi_logo' => [],
        'video' => null,
        'nhung_con_so' => [],
        'showroom_images' => [],
        'showroom_noidung' => null,
        'is_ecommerce_enabled' => true,
    ]);
}

function setEcommerceEnabled(bool $enabled): void
{
    $trangChu = ensureTrangChuRecord();
    $trangChu->update(['is_ecommerce_enabled' => $enabled]);
    Cache::forget('site_ecommerce_enabled');
}

test('ecommerce flag defaults to enabled after migrate', function () {
    $trangChu = ensureTrangChuRecord();

    expect($trangChu->is_ecommerce_enabled)->toBeTrue();
});

test('cart routes work when ecommerce is enabled', function () {
    setEcommerceEnabled(true);

    $product = LinhVatPhongThuyCt::query()->create([
        'name' => 'Linh vật toggle on',
        'code' => 'LV-ON-001',
        'price' => 100000,
        'images' => [],
        'is_delete' => 0,
    ]);

    $this->get(route('client.cart.index'))->assertSuccessful();

    $this->postJson(route('client.cart.add'), [
        'product_type' => 'linh_vat_phong_thuy_ct',
        'product_id' => $product->linh_vat_phong_thuy_ct_id,
        'qty' => 1,
    ])->assertSuccessful();
});

test('cart routes are blocked when ecommerce is disabled', function () {
    setEcommerceEnabled(false);

    $this->get(route('client.cart.index'))
        ->assertRedirect(route('client.home'));

    $this->get(route('client.cart.checkout'))
        ->assertRedirect(route('client.home'));

    $this->get(route('client.dich-vu.trang-thai-don-hang'))
        ->assertRedirect(route('client.home'));

    $this->postJson(route('client.cart.add'), [
        'product_type' => 'linh_vat_phong_thuy_ct',
        'product_id' => 1,
        'qty' => 1,
    ])->assertForbidden()
        ->assertJsonPath('status', 'error');
});

test('home page hides mini cart and shows consultation labels when ecommerce is off', function () {
    setEcommerceEnabled(false);

    LinhVatPhongThuy::query()->create([
        'thumbnail_main' => 'linh_vat_phong_thuy/images/banner.png',
        'video' => 'https://example.com/video.mp4',
    ]);

    LinhVatPhongThuyCt::query()->create([
        'name' => 'Linh vật showcase',
        'code' => 'LV-SHOW-001',
        'price' => 100000,
        'images' => ['assets/images/ngoi-01.jpg'],
        'des' => [],
        'is_delete' => 0,
    ]);

    $this->get(route('client.home'))
        ->assertSuccessful()
        ->assertDontSee('data-mini-cart', false);

    $this->get(route('client.products.linh-vat-phong-thuy.index'))
        ->assertSuccessful()
        ->assertSee('Liên hệ');
});

test('home page shows mini cart when ecommerce is enabled', function () {
    setEcommerceEnabled(true);

    $this->get(route('client.home'))
        ->assertSuccessful()
        ->assertSee('data-mini-cart', false);
});

test('updating trang chu busts ecommerce cache', function () {
    setEcommerceEnabled(true);

    app(TrangChuService::class)->update([
        'is_ecommerce_enabled' => false,
    ]);

    expect(Cache::get('site_ecommerce_enabled'))->toBeNull();

    $this->get(route('client.cart.index'))
        ->assertRedirect(route('client.home'));
});
