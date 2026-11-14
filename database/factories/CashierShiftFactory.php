<?php

namespace Database\Factories;

use App\Models\CashierShift;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CashierShift>
 */
class CashierShiftFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cashier_id' => User::factory()->cashier(),
            'started_at' => now(),
            'ended_at' => null,
            'starting_cash' => 200000,
            'total_cash_expected' => 200000,
            'total_cash_actual' => null,
            'total_sales' => 0,
            'status' => 'open',
            'notes' => null,
        ];
    }
}
