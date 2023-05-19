<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Category;
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
            'price' => $faker->numberBetween(10, 6000),
            'shop_id' => $faker->randomElement($shops),
            'status' => 'active'
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {

            $client = new Client();

            $main = true;
            for ($i=0; $i < 4; $i++) {

                $client->post('http://localhost:8080/api/insert', [
                    'json' => [
                        'name' => $product->name,
                        'path' => "images/products/" . rand(1, 4) . ".jpg",
                        'product_id' => $product->id,
                        'main' => $main,
                    ]

                ]);
                $main = false;

            }

        });
    }
}
