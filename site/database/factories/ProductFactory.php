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
        $shop = Shop::where('id', 1)->first();
        $shop2 = Shop::where('id', 2)->first();
        $shops = [$shop->id, $shop2->id];

        return [
            // 'user_id' => User::factory(),
            'name' => $faker->word(),
            'description' => $faker->sentence(),
            'tag' => $faker->name(),
            'image' =>  "images/products/".rand(1, 4).".jpg",
            'price' => $faker->numberBetween(10, 6000),
            'shop_id' => $faker->randomElement($shops),
            'hidden' => false
        ];
    }
}
