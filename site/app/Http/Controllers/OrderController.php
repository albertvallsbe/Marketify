<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Chat;

use App\Models\Shop;
use App\Models\Order;
use App\Models\Message;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItems;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Classes\HeaderVariables;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        try {
            $categories = Category::all();
            $shops = Shop::all();
            $products = Product::all();

            $shopName = array();
            for ($i=0 ; $i< count($shops); $i++) {
                $shopName[$i] = $shops[$i]->shopname;
            }

            $productsByShop = Order::findShopAndCartProducts();


            return view('orders.index', [
                'categories' => $categories,
                'options_order' => HeaderVariables::$order_array,
                'products' => $products,
                'shops' => $shops,
                'productsByShop' => $productsByShop,
                'shopName' => $shopName,
            ]);
        } catch (\Exception $e) {
                Log::channel('marketify')->error('An error occurred in OrderController: '.$e->getMessage());
                return redirect()->back()->with('error', 'An error occurred in OrderController.');
        }
    }


    public function add() {
        try{
            $productsByShop = Order::findShopAndCartProducts();
            $shops = Shop::all();
            $shopName = array();
            for ($i=0 ; $i< count($shops); $i++) {
                $shopName[$i] = $shops[$i]->id;
            }
            foreach ($productsByShop as $key => $shopByProduct) {
                $shopId = $shops[$key]->id;
                    $order = Order::create([
                        'user_id' => auth()->id(),
                        'shop_id' => $shopId
                    ]);
            $chat = Chat::createChatByOrder($shops[$key], $order->id);
            foreach ($shopByProduct as $key => $products) {
                OrderItems::create([
                    'order_id' => $order->id,
                    'shop_id' => $shopId,
                    'product_id' => $products->id
                ]);
                // Modificar el campo "status" del producto a "sold"
                $product = Product::find($products->id);
                if ($product) {
                    $product->status = 'sold';
                    $product->save();
                }
            }
        }
            return redirect()->route('chat.index');
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred creating orders: '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred in OrderController.');
        }
    }
}
