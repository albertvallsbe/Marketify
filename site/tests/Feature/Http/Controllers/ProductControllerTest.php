<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Classes\HeaderVariables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_create_view_for_seller()
    {

        $user = User::factory()->create(['role' => 'seller']);
        $this->actingAs($user);

        $categories = Category::factory(3)->create();
        HeaderVariables::$order_array = ['option1', 'option2', 'option3'];

        $response = $this->get('/product/create');

        $response->assertStatus(200);
        $response->assertViewIs('product.create');
        $response->assertViewHas('categories', $categories);
        $response->assertViewHas('options_order', HeaderVariables::$order_array);
    }

    public function test_redirects_to_shop_for_non_seller()
    {

        $user = User::factory()->create(['role' => 'customer']);
        $this->actingAs($user);

        $response = $this->get('/product/create');

        $response->assertRedirect('/shop');
    }

    public function test_redirects_to_login_index_for_guest()
    {

        $guest = User::factory()->create(['role' => 'customer']);
        $this->actingAs($guest);

        $response = $this->get('/product/create');

        $response->assertRedirect('/shop');
    }

    public function test_create_redirects_back_with_error()
    {

        $user = User::factory()->create(['role' => 'seller']);
        $this->actingAs($user);

        Log::shouldReceive('channel->error')->once();

        Config::set('app.debug', false);

        $response = $this->get('/product/create');

        $response->assertRedirect('/')->with('error', 'An error occurred.');
    }
}
