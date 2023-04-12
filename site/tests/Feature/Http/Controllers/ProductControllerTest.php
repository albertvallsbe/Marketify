<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class ProductControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_store(): void
    {
        $user = User::factory()->create();
        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'tag' => Category::factory()->create()->name,
            'image' => "images/products/".rand(1, 4).".jpg",
            'price' => $this->faker->numberBetween(10, 6000)
        ];

        $this
            ->actingAs($user)
            ->post('products', $data)
            ->assertRedirect('products');
        $this
            ->assertDatabaseHas('products', $data);
    }

    public function test_update(): void
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();
        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'tag' => Category::factory()->create()->name,
            'image' => "images/products/".rand(1, 4).".jpg",
            'price' => $this->faker->randomFloat(2, 10, 6000)
        ];

        $this
            ->actingAs($user)
            ->put("products/$product->id", $data)
            ->assertRedirect("products/$product->id/edit", "Error al actualizar el producto: los datos no son válidos");
        $this
            ->assertDatabaseHas('products', $data);
    }
}
