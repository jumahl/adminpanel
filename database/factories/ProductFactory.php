<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
class ProductFactory extends Factory

{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'quantity_in_stock' => $this->faker->numberBetween(0, 100),
            'photo' => 'products/default.jpg',
        ];
    }
}