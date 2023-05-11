<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Chat;

use App\Models\Shop;
use App\Models\Order;
use App\Models\Message;
use App\Models\Product;
use App\Models\Category;
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
        // try {
            $categories = Category::all();
            $userId = auth()->id();
            //coge el usuario
            $cart = Cart::showCartByUserID($userId);
            // dd($cart);
            $productIds = Order::decodeIds($cart);
            // dd($productIds);

            $shops = Shop::all();

            foreach ($shops as $shop) {
                $shopname = $shop->shopname;
            }

            $products = Product::all();

            // $productIds = Order::decodeIds($cart->products);
            $productsByShop = array();
            $shopName = array();
            for ($i=0 ; $i< count($shops); $i++) {
                $firstProduct = true;
                $shopName[$i] = $shops[$i]->shopname;
                for ($j=0 ; $j< count($productIds); $j++) {
                    $product = $products->where('id', $productIds[$j])->first();
                    if ($product && $product->shop_id == $shops[$i]->id) {
                        $productsByShop[$i][$j] = $product;
                        if($firstProduct){
                            $seller_id = $shops[$i]->user_id;
                            $customer_id = auth()->id();
                            $chat = Chat::chatChecker($seller_id, $customer_id);
                            if($chat === null){
                                $chat = Chat::create([
                                    'seller_id' => $seller_id,
                                    'customer_id' => $customer_id
                                ]);
                            }else{
                                $order = Order::getByChatID($chat->id);
                                $order->update([
                                    'status' => 'pending'
                                ]);
                            }

                        $message = Message::create([
                            'chat_id' => $chat->id,
                            'sender_id' => $customer_id,
                            'automatic' => true,
                            'content' => 'Order #XXX has been confirmed. Seller must accept payment and send the products.'
                        ]);
                        $notification = Notification::create([
                            'user_id' => $seller_id,
                            'chat_id' => $chat->id,
                            'read' => false
                        ]);
                        $firstProduct = false;
                        }
                    }
                }
            }
            // dd($productsByShop);
            return view('orders.index', [
                'categories' => $categories,
                'options_order' => HeaderVariables::$order_array,
                'cart' => $cart,
                'products' => $products,
                'productIds' => $productIds,
                // 'shopname' => $shopname,
                'shops' => $shops,
                'productsByShop' => $productsByShop,
                'shopName' => $shopName,
                // 'cartProducts' => $cartProducts
                // 'shop' => $shop,
                // 'cartProducts' => $arrayCart,
                // 'arrayProductsId' => $arrayProducts
            ]);
    }


    public function add(Request $request) {
        $productsArray = $request->input('orders');
        $userId = auth()->id();
        if($userId){
            Order::updateOrdersDB($productsArray);
        }
    }
}
