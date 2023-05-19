<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = Faker::create('es_ES');
        $userIds = User::pluck('id');
        $user_id = $faker->unique()->randomElement($userIds);
        $shop_name = User::pluck('name');
        $shopname = $faker->unique()->randomElement($shop_name);
        $nifNumber = mt_rand(10000000, 99999999);
        $nifLetter = chr(mt_rand(65, 90));
        $nif = $nifNumber . $nifLetter;

        return [
            'shopname' => $shopname,
            'username' => $faker->unique()->name(),
            'url' => $shopname."shop",
            'nif' => $nif,
            'user_id' => $user_id,
        ];
    }
}
