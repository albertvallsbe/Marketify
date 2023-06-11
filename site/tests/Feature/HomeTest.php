<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_no_auth_can_see_all_products()
    {
        
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);
        
        $seller2 = $this->createSeller();
        $shop2 = $this->createShop($seller2);
        $products2 = $this->createProducts($shop2);

        $response = $this->get(route('product.index'));

        $response->assertStatus(200)
                 ->assertSee('Add to cart');
    }

    public function test_customer_can_see_all_products()
    {
        
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);
        
        $seller2 = $this->createSeller();
        $shop2 = $this->createShop($seller2);
        $products2 = $this->createProducts($shop2);

        $customer = $this->createCustomer();
        $this->actingAs($customer);
        
        $response = $this->get(route('product.index'));

        $response->assertStatus(200)
                 ->assertSee('Add to cart');
    }

    public function test_user_cant_add_their_own_products()
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);

        $this->actingAs($seller);

        $response = $this->get(route('product.index'));
        $response->assertStatus(200)
                 ->assertDontSee('Add to cart');
    }

    public function test_hidden_products_are_not_visible(): void
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);
        $hidden_product = $this->createHiddenProduct($shop);

        $customer = $this->createCustomer();
        $this->actingAs($customer);

        $response = $this->get(route('product.index'));
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