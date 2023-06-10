<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShopViewTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_shop_and_his_products_are_visible(): void
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);

        $customer = $this->createCustomer();
        $this->actingAs($customer);

        $response = $this->get(route('shop.show',$shop->url));
        $response->assertSee($products[0]->name);
    }

    
    public function test_only_are_products_of_the_same_shop(): void
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        
        $another_seller = $this->createSeller();
        $another_shop = $this->createShop($another_seller);
        
        $products = $this->createProducts($shop);
        $another_products = $this->createProducts($another_shop);
        
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        
        $response = $this->get(route('shop.show',$shop->url));
        $response->assertSee($products[0]->name);
        $response->assertDontSee($another_products[0]->name);
    }
    
    public function test_hidden_products_are_not_visible(): void
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);
        $hidden_product = $this->createHiddenProduct($shop);

        $customer = $this->createCustomer();
        $this->actingAs($customer);

        $response = $this->get(route('shop.show',$shop->url));
        $response->assertDontSee($hidden_product);
    }

    // ************* \\

    public static function createCustomer()
    {   
        $user = User::factory()->create();
        return $user;
    }

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

    public static function createHiddenProduct($shop)
    {
        $product = Product::factory()->create(['status' => 'hidden']);
        return $product;
    }
}
