<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $price = 28000;
        $qty = fake()->numberBetween(1, 3);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'custom_menu_id' => null,
            'name' => fake()->words(2, true),
            'price' => $price,
            'quantity' => $qty,
            'subtotal' => $price * $qty,
            'options' => null,
            'notes' => null,
        ];
    }
}
