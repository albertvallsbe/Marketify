<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{

    public function definition()
    {
        $faker = Faker::create('es_ES');

        return [
            // 'user_id' => User::factory(),
            'name' => $faker->name(),
            'description' => $faker->sentence(),
            'tag' => Category::factory()->create()->name,
            'image' =>  "images/products/".rand(1, 4).".jpg",
            'price' => $faker->numberBetween(10, 6000)
        ];
    }
}
