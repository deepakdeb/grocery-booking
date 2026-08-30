<?php

namespace Database\Factories;

use App\Models\GroceryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroceryItemFactory extends Factory
{
    protected $model = GroceryItem::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 10, 100),
            'stock_quantity' => fake()->numberBetween(1, 30),
        ];
    }
}
