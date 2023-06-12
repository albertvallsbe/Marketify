<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItems;
use App\Classes\HeaderVariables;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class HistoricTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_returns_view_with_orders_when_user_logged_in()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $shop = Shop::factory()->create([
            'user_id' => $user->id,
        ]);

        $order1 = Order::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'status' => 'pending',
        ]);

        $order2 = Order::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'status' => 'pending',
        ]);

        $response = $this->get(route('historical.index'));

        $response->assertViewIs('order.historic');
        $response->assertViewHas('categories', Category::all());
        $response->assertViewHas('orders', Order::searchOrder($user->id, $shop->id));
        $response->assertViewHas('options_order', HeaderVariables::$order_array);
    }

    public function test_index_redirects_to_login_when_user_not_logged_in()
    {
        $response = $this->get(route('historical.index'));

        $response->assertRedirect(route('login.index'));
    }

    public function test_details_returns_view_with_order_details_when_user_owns_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $shop = Shop::factory()->create();

        $product = Product::factory()->create([
            'shop_id' => $shop->id,
        ]);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'status' => 'pending',
        ]);

        $orderItem1 = OrderItems::factory()->create([
            'order_id' => $order->id,
            'shop_id' => $shop->id,
            'product_id' => $product->id,

        ]);

        $orderItem2 = OrderItems::factory()->create([
            'order_id' => $order->id,
            'shop_id' => $shop->id,
            'product_id' => $product->id,

        ]);

        $response = $this->get(route('historical.details', ['id' => $order->id]));

        $response->assertViewIs('order.historicalDetails');
        $response->assertViewHas('categories', Category::all());
        $response->assertViewHas('order', $order);
        $response->assertViewHas('options_order', HeaderVariables::$order_array);
    }

    public function test_details_redirects_to_historic_index_when_user_does_not_own_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $user1 = User::factory()->create();
        $shop = Shop::factory()->create();

       $order = Order::factory()->create([
            'user_id' => $user1->id,
            'shop_id' => $shop->id,
            'status' => 'pending',
        ]);

        $response = $this->get(route('historical.details', ['id' => $order->id]));

        $response->assertRedirect(route('historical.index'));
    }

    public function test_downloadFile_generates_and_streams_pdf_when_user_owns_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $shop = Shop::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'status' => 'pending',
        ]);

        $orderItem1 = OrderItems::factory()->create([
            'order_id' => $order->id,
        ]);

        $orderItem2 = OrderItems::factory()->create([
            'order_id' => $order->id,
        ]);

        $response = $this->get(route('historical.downloadFile', ['id' => $order->id]));

        $response->assertOk();
    }

    public function test_downloadFile_redirects_to_order_index_when_user_does_not_own_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $user1 = User::factory()->create();
        $shop = Shop::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user1->id,
            'shop_id' => $shop->id,
            'status' => 'pending',
        ]);

        $response = $this->get(route('historical.downloadFile', ['id' => $order->id]));

        $response->assertRedirect(route('historical.index'));
    }
}
