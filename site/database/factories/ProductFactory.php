<?php

namespace Database\Factories;

use App\Models\Category;
use Faker\Factory as Faker;
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
        $faker = Faker::create('es_ES');
        return [

            'name' => $faker->unique()->name(),
            'description' => $faker->text,
            'tag' => Category::factory()->create()->name,
            'image' =>  "images/products/".rand(1, 4).".jpg",
            'price' => $faker->numberBetween(10, 6000)

        ];
    }
}
