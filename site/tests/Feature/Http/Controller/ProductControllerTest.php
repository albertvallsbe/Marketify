<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Category;

class ProductControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_store(): void
    {
        $data = [
            'name' => $this->faker->unique()->name(),
            'description' => $this->faker->text,
            'tag' => Category::factory()->create()->name,
            'image' => "images/products/".rand(1, 4).".jpg",
            'price' => $this->faker->numberBetween(10, 6000)
        ];

        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->post('products', $data)
            ->assertRedirect('products');

        $this
            ->assertDatabaseHas('products', $data);
    }

}
