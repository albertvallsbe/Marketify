<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RouteTest extends TestCase {

    use RefreshDatabase, WithFaker;

    public function testRouteHome() {
        $response = $this->get(route('product.index'));
        $response->assertStatus(200);
    }

    public function testRouteCreateProductNoUser() {
        $response = $this->get(route('product.create'));
        $response->assertStatus(302);
    }

    public function testRouteCreateProductWithUser() {
        $user = User::factory()->create(['role' => 'seller']);
        $shop = Shop::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id]);
        $this->actingAs($user);
        $response = $this->get(route('product.create'));
        $response->assertStatus(200);
    }

    public function testRouteShowProduct() {
        $user = User::factory()->create(['role' => 'seller']);
        $shop = Shop::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id]);
        $response = $this->get(route('product.show', $product->id));
        $response->assertStatus(200);
    }

    public function testRouteEditProductNoUser() {
        $user = User::factory()->create(['role' => 'seller']);
        $shop = Shop::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id]);
        $response = $this->get(route('product.edit', $product->id));
        $response->assertStatus(302);
    }

    public function testRouteEditProductWithUser() {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id]);
        $this->actingAs($user);
        $response = $this->get(route('product.edit', $product->id));
        $response->assertStatus(200);
    }

    public function testRouteEditProductWithOtherUser() {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);
        $user2 = User::factory()->create();
        $product = Product::factory()->create(['shop_id' => $shop->id]);
        $this->actingAs($user2);
        $response = $this->get(route('product.edit', $product->id));
        $response->assertStatus(302);
    }

    public function testRouteRegisterNoUser() {
        $response = $this->get(route('register.index'));
        $response->assertStatus(200);
    }
    
    public function testRouteRegisterWithUser() {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('register.index'));
        $response->assertStatus(302);
    }

    public function testRouteFormEmailNoUser() {
        $response = $this->get(route('login.formEmail'));
        $response->assertStatus(200);
    }

    public function testRouteFormEmailWithUser() {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('login.formEmail'));
        $response->assertStatus(302);
    }

    public function testRouteShowResetFormNoUser() {
        $response = $this->get(route('login.receivedEmail'));
        $response->assertStatus(200);
    }

    public function testRouteShowResetFormWithUser() {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('login.receivedEmail'));
        $response->assertStatus(302);
    }

    public function testRouteLoginNoUser() {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }

    public function testRouteLoginWithUser() {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('login.index'));
        $response->assertStatus(302);
    }

    public function testRouteUserEditNoUser() {
        $response = $this->get(route('user.edit'));
        $response->assertStatus(302);
    }

    public function testRouteUserEditWithUser() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('user.edit'));
        $response->assertStatus(200);
    }

    public function testRouteUserShow() {
        $user = User::factory()->create();
        $response = $this->get(route('user.show', $user->id));
        $response->assertStatus(200);
    }

    public function testRouteCart() {
        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);
    }

    public function testRouteShopAdminNoUser() {
        $response = $this->get(route('shop.admin'));
        $response->assertStatus(302);
    }

    public function testRouteShopAdminWithUser() {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);
        $response = $this->get(route('shop.admin'));
        $response->assertStatus(200);
    }
    
    public function testRouteShopAdminWithOtherUser() {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user2);
        $response = $this->get(route('shop.admin'));
        $response->assertStatus(302);
    }

    public function testRouteShopEditNoUser() {
        $response = $this->get(route('shop.edit'));
        $response->assertStatus(302);
    }

    public function testRouteShopEditWithUser() {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);
        $response = $this->get(route('shop.edit'));
        $response->assertStatus(200);
    }

    public function testRouteShopShow() {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);
        $response = $this->get(route('shop.show',$shop->url));
        $response->assertStatus(200);
    }

    public function testRouteShopNoUser() {
        $response = $this->get(route('shop.index'));
        $response->assertStatus(302);
    }

    public function testRouteShopWithUser() {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);
        $response = $this->get(route('shop.index'));
        $response->assertStatus(200);
    }

    public function testRouteLanding() {
        $response = $this->get(route('landing.index'));
        $response->assertStatus(302);
    }

    public function testRouteOrderNoUser() {
        $response = $this->get(route('order.index'));
        $response->assertStatus(302);
    }

    public function testRouteOrderWithUser() {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('order.index'));
        $response->assertStatus(200);
    }

    public function testRouteManagementNoUser() {
        $response = $this->get(route('order.management'));
        $response->assertStatus(302);
    }

    public function testRouteManagementWithUser() {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('order.management'));
        $response->assertStatus(200);
    }

    public function testRouteChatNoUser() {
        $response = $this->get(route('chat.index'));
        $response->assertStatus(302);
    }

    public function testRouteChatWithUser() {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('chat.index'));
        $response->assertStatus(200);
    }

    public function testRouteHistoricalNoUser() {
        $response = $this->get(route('historical.index'));
        $response->assertStatus(302);
    }

    public function testRouteHistoricalWithUser() {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('historical.index'));
        $response->assertStatus(200);
    }


    public function testRouteProductNotFound() {
        $response = $this->get(route('product.404'));
        $response->assertStatus(200);
    }

    public function testRouteShopNotFound() {
        $response = $this->get(route('shop.404'));
        $response->assertStatus(200);
    }

    public function testRouteUserNotFound() {
        $response = $this->get(route('user.404'));
        $response->assertStatus(200);
    }

    public function testRouteGenericNotFound() {
        $response = $this->get('/inexistent-route');
        $response->assertOk();
    }
}
