<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShopAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_product(): void
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $this->actingAs($seller);
        $data = [
            'name' => 'Lorem ipsum',
            'description' => 'Lorem ipsum dolor sit amet',
            'price' => 10,
            'tag' => 'Lorem'
        ];
        $createdProduct = Product::factory()->create($data);
        $createdProduct = Product::where('name', $data['name'])->first();
        
        $this->assertDatabaseHas('products', $data);
        $this->assertEquals($data['description'], $createdProduct->description);
        $this->assertEquals($data['price'], $createdProduct->price);
    }

    public function test_edit_product(): void
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $this->actingAs($seller);

        $product = Product::factory()->create();

        $data = [
            'name' => 'New Product Name',
            'description' => 'New Product Description',
            'price' => 20,
            'tag' => 'New Tag'
        ];
        $product->update($data);

        $this->assertEquals($data['name'], $product->name);
        $this->assertEquals($data['description'], $product->description);
        $this->assertEquals($data['price'], $product->price);
        $this->assertEquals($data['tag'], $product->tag);
    }

    public function test_delete_product(): void
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);

        $response = $this->delete(route('product.destroy', ['id' => $products[0]->id]));
        $this->assertDatabaseMissing('products', ['id' => $products[0]->id]);
    }
    

    // ************* \\

    public static function createSeller()
    {   
        $seller = User::factory()->create(['role' => 'seller']);
        return $seller;
    }

    public static function createShop($seller)
    {   
        $url = uniqid();
        $shop = Shop::factory()->create([
            'user_id' => $seller->id,
            'url' => $url,
        ]);
        return $shop;
    }

    public static function createProducts($shop)
    {
        $products = Product::factory(2)->create(['shop_id' => $shop->id]);
        return $products;
    }
}