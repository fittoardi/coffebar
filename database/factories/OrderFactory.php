<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_code' => Order::generateOrderCode(),
            'user_id' => User::factory(),
            'cashier_id' => null,
            'customer_name' => fake()->name(),
            'customer_phone' => '08'.fake()->numerify('##########'),
            'source' => 'online',
            'status' => 'menunggu',
            'total_amount' => 45000,
            'payment_method' => 'online',
            'payment_status' => 'pending',
            'notes' => fake()->sentence(),
            'paid_at' => null,
            'completed_at' => null,
        ];
    }
}
