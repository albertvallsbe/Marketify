<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

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
        $faker = Faker::create('es_ES'); 
        return [

            'name' => $faker->unique()->name(),
            'description' => $faker->unique()->address(),
            'tag' => $faker->words(2, true),
            'image' =>  "images/products/".rand(1, 4).".jpg",
            'price' => fake()->randomDigit()
            
        ];
    }
}
