<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItems;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemsFactory extends Factory
{
    protected $model = OrderItems::class;

    public function definition()
    {
        $user = User::factory()->create();
        $shop =  Shop::factory()->create([
            'user_id' => $user->id,
        ]);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'status' => 'pending',
        ]);
        $product =  Product::factory()->create();


        return [
            'order_id' => $order->id,
            'shop_id' => $shop->id,
            'product_id' => $product->id,
        ];
    }
}
