<?php

namespace Database\Factories;

use App\Models\CustomMenu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomMenu>
 */
class CustomMenuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => 'Kopi Racikan '.fake()->firstName(),
            'base' => fake()->randomElement(['Espresso', 'Cold Brew', 'Matcha', 'Earl Grey']),
            'size' => fake()->randomElement(['S', 'M', 'L']),
            'sugar_level' => fake()->randomElement(['0%', '50%', '100%']),
            'ice_level' => fake()->randomElement(['ice', 'hot']),
            'options' => ['Boba', 'Whipped Cream'],
            'price' => 32000,
            'notes' => fake()->sentence(),
        ];
    }
}
