<?php

use App\Domains\Catalog\Infrastructure\ProductWriter;
use Illuminate\Support\Facades\DB;

test('home page product links open the product detail page', function (string $type, string $routeName) {
    $product = app(ProductWriter::class)->create($type, [
        'name' => 'Sản phẩm trang chủ',
        'code' => 'HOME-LINK',
        'price' => 150000,
        'images' => [],
        'is_delete' => false,
    ]);

    // Public ids are numbered per product type, so they drift away from the row id.
    DB::table('product_public_ids')->where('product_id', $product->id)->update(['public_id' => $product->id + 500]);

    $detailUrl = route($routeName, $product->id + 500);

    $this->get(route('client.home'))
        ->assertOk()
        ->assertSee($detailUrl, false)
        ->assertDontSee(route($routeName, $product->id).'"', false);

    $this->get($detailUrl)->assertOk()->assertSee('Sản phẩm trang chủ');
})->with([
    ['ngoi_am_duong_ct', 'client.products.ngoi-am-duong.detail'],
    ['ngoi_hai_van_mieu_ct', 'client.products.ngoi-hai-van-mieu.detail'],
    ['gach_hoa_thong_gio_ct', 'client.products.gach-hoa-thong-gio.detail'],
]);
