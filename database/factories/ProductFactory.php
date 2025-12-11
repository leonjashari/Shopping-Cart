<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(12),
            'price' => $this->faker->randomFloat(2, 9, 199),
            'stock_quantity' => $this->faker->numberBetween(5, 50),
            'low_stock_threshold' => 5,
            'image_url' => null,
        ];
    }
}
