<?php

namespace Database\Factories;

use App\Models\ProductOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductOption>
 */
class ProductOptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => null,
            'type' => fake()->randomElement(['size', 'topping', 'addon', 'sugar', 'ice']),
            'name' => fake()->word(),
            'price' => fake()->randomElement([0, 3000, 5000, 7000]),
            'is_available' => true,
            'sort_order' => 1,
        ];
    }
}
