<?php

namespace App\Domains\Commerce\Infrastructure\Persistence;

use App\Domains\Commerce\Application\Ports\CouponRepositoryPort;
use App\Domains\Commerce\Application\Ports\OrderCheckoutPort;
use App\Domains\Commerce\Infrastructure\Mail\OrderCreatedMail;
use App\Domains\Commerce\Infrastructure\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EloquentOrderCheckoutAdapter implements OrderCheckoutPort
{
    public function __construct(private readonly CouponRepositoryPort $coupons) {}

    public function create(array $data, array $items, ?string $couponCode): string
    {
        $order = DB::transaction(function () use ($data, $items, $couponCode) {
            $order = Order::create([
                ...$data,
                'order_code' => Order::generateOrderCode(),
                'status' => 'processing',
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_type' => $item['product_type'],
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                    'product_name' => $item['name'],
                    'variant_name' => $item['variant_name'],
                    'sku' => $item['sku'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['total'],
                ]);
            }

            if ($couponCode !== null) {
                $this->coupons->incrementUsage($couponCode);
            }

            return $order;
        });

        if ($order->email) {
            Mail::to($order->email)->send(new OrderCreatedMail($order->load('items')));
        }

        return $order->order_code;
    }
}
