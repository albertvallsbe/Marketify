<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RouteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testRouteHome(): void
    {
        $response = $this->get('/');
        dump($response);
        $response->assertStatus(302);
    }

    public function testRouteProducts(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
    }

    public function testRouteCreateProduct(): void
    {
        $response = $this->get('/product/create');
        $response->assertStatus(200);
    }

    public function testRouteShowProduct(): void
    {
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
        $product = Product::factory()->create();
        $response = $this->get(route('product.show', $product->id));
        $response->assertStatus(200);
    }

    public function testRouteEditProduct(): void
    {
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
        $product = Product::factory()->create();
        $response = $this->get(route('product.edit', $product->id));
        $response->assertStatus(200);
    }

    public function testRouteRegister(): void
    {
        $response = $this->get(route('register.index'));
        $response->assertStatus(200);
    }

    public function testRoutePassword(): void
    {
        $response = $this->get(route('login.password'));
        $response->assertStatus(200);
    }

    public function testRoutePasswordRemember(): void
    {
        $response = $this->get(route('login.remember'));
        $response->assertStatus(200);
    }

    public function testRouteLogin(): void
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }

    public function testRouteUserEdit(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('user.edit'));
        $response->assertStatus(200);
    }

    public function testRouteUserShow(): void
    {
        $user = User::factory()->create();
        $response = $this->get(route('user.show', $user->id));
        $response->assertStatus(200);
    }

    public function testRouteCart(): void
    {
        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);
    }

    public function testRouteShopAdmin(): void
    {
        $response = $this->get(route('shop.admin'));
        $response->assertStatus(302);
    }

    public function testRouteShopEdit(): void
    {
        $response = $this->get(route('shop.edit'));
        $response->assertStatus(302);
    }

    public function testRouteShopShow(): void
    {
        $response = $this->get('/shop/1');
        $response->assertStatus(200);
    }

    public function testRouteShop(): void
    {
        $response = $this->get(route('shop.index'));
        $response->assertStatus(200);
    }

    public function testRouteLanding(): void
    {
        $response = $this->get(route('landing.index'));
        $response->assertStatus(200);
    }

    public function testRouteProductNotFound(): void
    {
        $response = $this->get(route('product.404'));
        $response->assertStatus(200);
    }

    public function testRouteShopNotFound(): void
    {
        $response = $this->get(route('shop.404'));
        $response->assertStatus(200);
    }

    public function testRouteUserNotFound(): void
    {
        $response = $this->get(route('user.404'));
        $response->assertStatus(200);
    }

    public function testRouteGenericNotFound(): void
    {
        $response = $this->get('/inexistent-route');
        $response->assertOk();
    }
}
