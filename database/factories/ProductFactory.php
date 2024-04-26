<?php

namespace Database\Factories;

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
        return [
            'product_name' => 'ABC',
            'product_price' => $this->faker->randomDigit(),
            'images' =>'',
            'product_description' => 'Random product description',
            'product_category' => 'Makanan',
            'product_status' => 'active'
        ];
    }
}
