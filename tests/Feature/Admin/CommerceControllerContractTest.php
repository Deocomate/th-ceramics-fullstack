<?php

use App\Domains\Commerce\Infrastructure\Models\ConsultationRequest;
use App\Domains\Commerce\Infrastructure\Models\Order;
use App\Domains\Content\Http\Middleware\EnsureContentWritesOpen;
use App\Domains\Identity\Models\User;

beforeEach(function () {
    $this->withoutMiddleware(EnsureContentWritesOpen::class);
    $this->actingAs(User::factory()->create(['role' => 'superadmin']));
});

test('coupon creation redirects to the preserved admin route', function () {
    $this->post(route('admin.coupons.store'), [
        'title' => 'Khuyến mãi tháng này',
        'code' => 'THANG-NAY',
        'discount_type' => 'fixed',
        'discount_value' => 50000,
        'min_order_value' => 100000,
        'usage_limit' => 10,
        'start_date' => now()->subDay()->toDateString(),
        'end_date' => now()->addDay()->toDateString(),
        'is_active' => true,
    ])->assertRedirect(route('admin.coupons.index'));
});

test('order status update redirects to the preserved admin route', function () {
    $order = Order::factory()->create([
        'email' => null,
        'status' => 'processing',
    ]);

    $this->put(route('admin.orders.update', $order), [
        'status' => 'shipping',
    ])->assertRedirect(route('admin.orders.show', $order));

    expect($order->fresh()->status)->toBe('shipping');
});

test('consultation deletion redirects to the preserved admin route', function () {
    $consultation = ConsultationRequest::query()->create([
        'customer_name' => 'Khách cần tư vấn',
        'phone' => '0900000000',
        'status' => 'pending',
    ]);

    $this->delete(route('admin.consultation-requests.destroy', $consultation))
        ->assertRedirect(route('admin.consultation-requests.index'));

    expect($consultation->fresh())->toBeNull();
});
