<?php

use App\Domains\Catalog\Infrastructure\ProductWriter;
use App\Domains\Commerce\Infrastructure\Mail\OrderCreatedMail;
use App\Domains\Commerce\Infrastructure\Models\Coupon;
use App\Domains\Commerce\Infrastructure\Models\Order;
use App\Domains\Identity\Infrastructure\Models\User;
use Illuminate\Support\Facades\Mail;

test('checkout preserves cart product details in the order snapshot and sends its mail', function () {
    Mail::fake();
    $user = User::factory()->create(['email_verified_at' => now()]);
    $product = app(ProductWriter::class)->create('linh_vat_phong_thuy_ct', [
        'name' => 'Linh vật order snapshot',
        'code' => 'LV-ORDER-001',
        'price' => 275000,
        'images' => ['assets/images/linh-vat.png'],
        'is_delete' => 0,
    ]);

    $this->actingAs($user)->postJson(route('client.cart.add'), [
        'product_type' => 'linh_vat_phong_thuy_ct',
        'product_id' => $product->linh_vat_phong_thuy_ct_id,
        'qty' => 2,
    ])->assertSuccessful()->assertJsonPath('cart_count', 2);

    $this->get(route('client.cart.index'))
        ->assertSuccessful()
        ->assertSee('Linh vật order snapshot')
        ->assertSee('x2');

    $this->post(route('client.cart.checkout.process'), [
        'customer_name' => 'Nguyen Van A',
        'phone' => '0909123456',
        'email' => 'buyer@example.com',
        'address' => 'Ha Noi',
        'payment_method' => 'cod',
    ])->assertRedirect(route('client.home'));

    $order = Order::query()->with('items')->firstOrFail();
    expect($order->user_id)->toBe($user->id)
        ->and($order->subtotal)->toBe(550000)
        ->and($order->total_amount)->toBe(550000)
        ->and($order->status)->toBe('processing')
        ->and($order->items)->toHaveCount(1)
        ->and($order->items->first()->product_name)->toBe('Linh vật order snapshot')
        ->and($order->items->first()->quantity)->toBe(2)
        ->and($order->items->first()->total)->toBe(550000);

    Mail::assertQueued(OrderCreatedMail::class);
    $this->getJson(route('client.cart.mini'))->assertJsonPath('cart_count', 0);
});

test('coupon discount and usage are applied to the checkout snapshot', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $product = app(ProductWriter::class)->create('linh_vat_phong_thuy_ct', [
        'name' => 'Linh vật coupon',
        'code' => 'LV-COUPON-001',
        'price' => 275000,
        'images' => [],
        'is_delete' => 0,
    ]);
    Coupon::query()->create([
        'title' => 'Giảm cho linh vật',
        'code' => 'LV50K',
        'discount_type' => 'fixed',
        'discount_value' => 50000,
        'min_order_value' => 1,
        'applicable_product_types' => ['linh_vat_phong_thuy_ct'],
        'usage_limit' => 1,
        'used_count' => 0,
        'start_date' => now()->subDay(),
        'is_active' => true,
        'is_delete' => false,
    ]);

    $this->actingAs($user)->postJson(route('client.cart.add'), [
        'product_type' => 'linh_vat_phong_thuy_ct',
        'product_id' => $product->linh_vat_phong_thuy_ct_id,
        'qty' => 2,
    ])->assertSuccessful();

    $this->postJson(route('client.cart.coupon.apply'), ['code' => 'LV50K'])
        ->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonPath('discount', 50000)
        ->assertJsonPath('new_total', 500000);

    $this->post(route('client.cart.checkout.process'), [
        'customer_name' => 'Nguyen Van A',
        'phone' => '0909123456',
        'address' => 'Ha Noi',
        'payment_method' => 'cod',
    ])->assertRedirect(route('client.home'));

    $order = Order::query()->firstOrFail();
    expect($order->discount)->toBe(50000)
        ->and($order->total_amount)->toBe(500000)
        ->and($order->coupon_code)->toBe('LV50K')
        ->and(Coupon::query()->firstOrFail()->used_count)->toBe(1);
});
