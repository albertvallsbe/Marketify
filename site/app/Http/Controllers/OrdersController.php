<?php

namespace App\Http\Controllers;

use App\Models\Cart;

use App\Models\Shop;
use App\Classes\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        try {
            $categories = Category::all();

            $user = Auth::user();
            $orders = Order::with('shop_id')->where('user_id', $user->id)->orderByDesc('created_at')->get();

            $carts = Cart::where('user_id', $user->id)->get();

            $cartProducts = [];
            foreach ($carts as $cart) {
                $cartProductIds = json_decode($cart->products, true);

                foreach ($cartProductIds as $shopId => $productIds) {
                    $shop = Shop::findOrFail($shopId);

                    foreach ($productIds as $productId) {
                        $product = Product::findOrFail($productId);
                        $cartProducts[] = [
                            'product' => $product,
                            'shop' => $shop,
                        ];
                    }
                }
            }

            return view('orders.index', [
                'categories' => $categories,
                'options_order' => Order::$order_array,
                'orders',
                'cartProducts'
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
