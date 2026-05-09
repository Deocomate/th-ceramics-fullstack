<?php

namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $price = fake()->numberBetween(5000, 500000);
        $qty = fake()->numberBetween(1, 100);

        return [
            'product_type' => fake()->randomElement(['ngoi_am_duong_ct', 'ngoi_hai_van_mieu_ct', 'gach_hoa_thong_gio_ct']),
            'product_id' => fake()->numberBetween(1, 100),
            'variant_id' => fake()->optional()->numberBetween(1, 10),
            'product_name' => fake()->words(3, true),
            'variant_name' => fake()->optional()->colorName(),
            'sku' => strtoupper(fake()->bothify('???-###')),
            'price' => $price,
            'quantity' => $qty,
            'total' => $price * $qty,
        ];
    }
}
