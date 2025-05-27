<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $initial_price = fake()->numberBetween(10000, 200_000);

        return [
            'category_id' => Category::all()->random()->id,
            'name' => fake()->company(),
            'barcode' => 899 . fake()->numberBetween(1_000_000_000,9_999_999_999),
            'stock' => fake()->numberBetween(1, 50),
//            'is_non_stock' => fake()->name(),
            'initial_price' => $initial_price,
            'selling_price' => fake()->numberBetween($initial_price, $initial_price * 1.5),
            'unit' => 'PCS',
            'type' => 'product',
        ];
    }
}
