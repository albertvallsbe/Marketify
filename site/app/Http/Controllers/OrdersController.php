<?php

namespace App\Http\Controllers;

use App\Models\Cart;

use App\Models\Shop;
use App\Classes\Order;
use App\Models\Message;
use App\Models\Product;
use App\Models\Category;
use App\Models\Chat;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        try {
            $seller_id = 3;
            $customer_id = auth()->id();
            $chat = Chat::chatChecker($seller_id, $customer_id);
            if($chat === null){ 
                $chat = Chat::create([
                    'seller_id' => $seller_id,
                    'customer_id' => $customer_id
                ]);
            }else{
                $chat->update([
                    'paymentDone' => false,
                    'shipmentSend' => false,
                    'shipmentDone' => false
                ]);
            }
            $message = Message::create([
                'chat_id' => $chat->id,
                'sender_id' => $customer_id,
                'automatic' => true,
                'content' => 'Order #XXX has been confirmed. Seller must accept payment and send the products.'
            ]);

            /*
            $message = Message::create([
                'chat_id' => $chat->id,
                'sender_id' => $customer_id,
                'content' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente totam itaque at distinctio nam nobis praesentium repellendus possimus eum in cum doloremque, error sint repellat quisquam accusamus, aliquid veniam animi."
            ]);
            
            $message = Message::create([
                'chat_id' => $chat->id,
                'sender_id' => $seller_id,
                'content' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente totam itaque at distinctio nam nobis praesentium repellendus possimus eum in cum doloremque, error sint repellat quisquam accusamus, aliquid veniam animi."
            ]);
            */
            
            $notification = Notification::create([
                'user_id' => $seller_id,
                'chat_id' => $chat->id,
                'read' => false
            ]);





            $categories = Category::all();


            return view('orders.index', [
                'categories' => $categories,
                'options_order' => Order::$order_array
            ]);
        } catch (\Exception $e) {
                Log::channel('marketify')->error('An error occurred while loading the home view: '.$e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while loading the home view.');
            }
    }

    public function getProducts($id)
    {
        try{
            Log::channel('marketify')->debug('getProducts has been loaded successfully with this category: ');

            return Product::query()
            ->select('products.shop_id')
            ->where('id', '=', $id)
            ->get();

        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred while loading getProducts() '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading getProducts() ');
        }
    }
}
