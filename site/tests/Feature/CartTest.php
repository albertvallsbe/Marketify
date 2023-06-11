<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_can_be_null()
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);

        $response = $this->get(route('cart.index'));

        $response->assertStatus(200)
                 ->assertSee('No results were found.');
    }

    public function test_user_no_auth_cart_can_contain_products()
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);

        $response = $this->get('/cart?id=1,2');

        // Me marca como error si pongo el texto junto,
        // no sé si será por ser una etiqueta <a>Log In</a>...
        $response->assertStatus(200)
                 ->assertSee($products[0]->name)
                 ->assertSee($products[1]->name)
                 ->assertSee('Log in')
                 ->assertSee('to buy on our website.');
    }

    public function test_customer_cart_can_contain_products()
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);

        
        $customer = $this->createCustomer();
        $this->actingAs($customer);

        $response = $this->get('/cart?id=1,2');

        // Me marca como error si pongo el texto junto,
        // no sé si será por ser una etiqueta <a>Log In</a>...
        $response->assertStatus(200)
                 ->assertSee($products[0]->name)
                 ->assertSee($products[1]->name)
                 ->assertDontSee('Log in')
                 ->assertDontSee('to buy on our website.');
    }

    public function test_customer_cant_buy_hidden_products()
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);
        $hidden_product = $this->createHiddenProduct($shop);

        
        $customer = $this->createCustomer();
        $this->actingAs($customer);

        $response = $this->get('/cart?id=1,2,3');

        $response->assertStatus(200)
                 ->assertSee("Some products are marked as disabled by their owner. Please delete them to proceed.");
    }

    public function test_customer_cant_buy_sold_products()
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);
        $sold_product = $this->createSoldProduct($shop);

        
        $customer = $this->createCustomer();
        $this->actingAs($customer);

        $response = $this->get('/cart?id=1,2,3');

        $response->assertStatus(200)
                 ->assertSee("Some products have been sold. Please delete them to proceed.");
    }

    public function test_seller_cant_buy_own_products()
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);
        
        $this->actingAs($seller);

        $response = $this->get('/cart?id=1,2,3');

        $response->assertStatus(200)
                 ->assertSee("Some products are marked as if you are the owner. Please delete them to proceed.");
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

    public static function createSoldProduct($shop)
    {
        $product = Product::factory()->create(['status' => 'sold']);
        return $product;
    }
}