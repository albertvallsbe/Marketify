<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrdersItemsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_order_item_belongs_to_order()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create([
            'user_id' => $user->id
        ]);
        $order = Order::factory()->create();
        $product = Product::factory()->create([
            'shop_id' => $shop->id,
        ]);

        $orderItem = $order->orderItems()->create([
            'order_id' => $order->id,
            'shop_id' => $shop->id,
            'product_id' => $product->id,
        ]);

        // Verificar que el order_item pertenece a la orden
        $this->assertEquals($order->id, $orderItem->order_id);
    }

    public function test_order_item_belongs_to_product()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create([
            'user_id' => $user->id
        ]);
        $order = Order::factory()->create();
        $product = Product::factory()->create([
            'shop_id' => $shop->id,
        ]);

        $orderItem = $order->orderItems()->create([
            'order_id' => $order->id,
            'shop_id' => $shop->id,
            'product_id' => $product->id,
        ]);

        $this->assertEquals($product->id, $orderItem->product_id);
    }

    public function test_order_item_belongs_to_shop()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create([
            'user_id' => $user->id
        ]);
        $order = Order::factory()->create();
        $product = Product::factory()->create([
            'shop_id' => $shop->id,
        ]);

        $orderItem = $order->orderItems()->create([
            'order_id' => $order->id,
            'shop_id' => $shop->id,
            'product_id' => $product->id,
        ]);

        $this->assertEquals($shop->id, $orderItem->shop_id);
    }
}
