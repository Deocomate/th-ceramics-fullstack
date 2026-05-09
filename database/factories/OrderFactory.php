<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(1000000, 50000000);

        return [
            'order_code' => Order::generateOrderCode(),
            'customer_name' => fake()->name(),
            'phone' => '0' . fake()->numerify('#########'),
            'email' => fake()->optional()->safeEmail(),
            'address' => fake()->address(),
            'note' => fake()->optional()->sentence(),
            'subtotal' => $subtotal,
            'shipping_fee' => 0,
            'discount' => 0,
            'total_amount' => $subtotal,
            'payment_method' => fake()->randomElement(['cod', 'banking']),
            'status' => fake()->randomElement(['pending_payment', 'processing', 'shipping', 'completed', 'canceled']),
        ];
    }
}
