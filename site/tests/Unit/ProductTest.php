<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_searchAll()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create([
            'user_id' => $user->id,
        ]);
        $product1 = Product::factory()->create([
            'shop_id' => $shop->id,
            'name' => 'Product 1',
        ]);
        $product2 = Product::factory()->create([
            'shop_id' => $shop->id,
            'name' => 'Product 2',
        ]);
        $product3 = Product::factory()->create([
            'shop_id' => $shop->id,
            'name' => 'Product 3',
        ]);

        $searchTerm = 'Product';
        $results = Product::searchAll($searchTerm);

        $this->assertCount(3, $results);

    }

    public function test_getPrice()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create([
            'user_id' => $user->id
        ]);
        $product = Product::factory()->create([
            'price' => 10.99,
            'shop_id' => $shop->id,
        ]);
        // Obtener el precio del producto por su ID
        $getProductPrice = Product::getPrice($product->id);

        $this->assertEquals($product->price, $getProductPrice->price);
    }
}
