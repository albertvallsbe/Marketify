<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Shop;
use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chat::class;

    public function definition()
    {
        $seller = User::factory()->create();
        $shop = Shop::factory()->create([
            'user_id' => $seller->id,
        ]);
        $customer = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'shop_id' => $shop->id,
            'status' => 'pending',
        ]);

        return [
            'seller_id' => $seller->id,
            'customer_id' => $customer->id,
            'order_id' => $order->id,
        ];
    }
}
