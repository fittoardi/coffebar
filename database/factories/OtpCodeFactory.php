<?php

namespace Database\Factories;

use App\Models\OtpCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<OtpCode>
 */
class OtpCodeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'identifier' => fake()->safeEmail(),
            'channel' => 'email',
            'code_hash' => Hash::make('123456'),
            'attempts' => 0,
            'max_attempts' => 3,
            'expires_at' => now()->addMinutes(5),
            'verified_at' => null,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subMinutes(1),
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => now(),
        ]);
    }

    public function maxAttemptsExceeded(): static
    {
        return $this->state(fn (array $attributes) => [
            'attempts' => 3,
        ]);
    }
}
