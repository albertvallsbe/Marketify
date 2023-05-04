<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrdersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => false,
            'user_id' => rand(1, 10),
            'shop_id' => rand(1, 5),
            'products' => '[186,207,428,709,90,726,186]',
        ];
    }
}
