<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    // public function test_store(): void
    // {

    //     $user = User::factory()->create();
    //     $shop = Shop::factory()->create([
    //         'user_id' => $user->id
    //     ]);

    //     $data = [
    //         'name' => $this->faker->name(),
    //         'description' => $this->faker->sentence(),
    //         'tag' => Category::factory()->create()->name,
    //         'shop_id' => $shop->id,
    //         'price' => $this->faker->numberBetween(10, 6000),
    //         'status' => 'active'
    //     ];

    //     $this
    //         ->actingAs($user)
    //         ->post('products', $data);
    //         // ->assertRedirect('products');

    //     $this
    //         ->assertDatabaseHas('products', $data);
    // }

}
