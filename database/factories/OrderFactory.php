<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_date' => now(),
            'total' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(['pendiente', 'en_proceso', 'entregado', 'cancelado', 'carrito'])
        ];
    }
}