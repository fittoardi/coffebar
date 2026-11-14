<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'amount' => 45000,
            'method' => 'online',
            'status' => 'pending',
            'gateway_name' => 'midtrans',
            'gateway_reference' => 'MID-'.fake()->numerify('######'),
            'gateway_payload' => null,
            'cash_given' => null,
            'change_given' => null,
            'paid_at' => null,
        ];
    }
}
