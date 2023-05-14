<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Shop;
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
        $name = $faker->unique()->words($nb = $faker->numberBetween(1, 2), $asText = true);
        while (strlen($name) < 5) {
            $name = $faker->unique()->words($nb = $faker->numberBetween(1, 2), $asText = true);
        }
        $shops = Shop::all();

        return [
            'name' => $name,
            'description' => $faker->sentence(20),
            'tag' => $faker->name(),
            'image' =>  "images/products/".rand(1, 4).".jpg",
            'price' => $faker->numberBetween(10, 6000),
            'shop_id' => $faker->randomElement($shops),
            'status' => 'active'
        ];
    }
}
