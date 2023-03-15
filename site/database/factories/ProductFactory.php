<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name_product' => fake()->unique()->words(4, true),
            'description' => fake()->paragraph(1),
            'tag' => fake()->unique()->words(4, true),
            'image' =>  "images/products/".rand(1, 4).".jpg",
            'price' => fake()->randomDigit()

        ];
    }
}
