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
        $shop = Shop::where('id', 1)->first();
        $shop2 = Shop::where('id', 2)->first();
        $shops = [$shop->id, $shop2->id];

        return [
            // 'user_id' => User::factory(),
            'name' => $faker->word(),
            'description' => $faker->sentence(),
            'tag' => $faker->name(),
            'image' =>  "images/products/" . rand(1, 4) . ".jpg",
            'price' => $faker->numberBetween(10, 6000),
            'shop_id' => $faker->randomElement($shops),
            'status' => 'active'
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            // URL de la API
            $url = 'http://localhost:8080/api/insert';

            // Crear instancia de Guzzle HTTP Client
            $client = new Client();

            // Realizar petición HTTP GET a la API
            $response = $client->post($url);

            // Obtener el contenido de la respuesta
            $contenido = $response->getBody()->getContents();

            return $contenido;
        });
    }
}
