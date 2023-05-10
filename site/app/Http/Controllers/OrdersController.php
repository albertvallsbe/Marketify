<?php

namespace App\Http\Controllers;

use App\Classes\Order;
use App\Models\Cart;
use App\Models\Shop;
use App\Models\Orders;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function index(Request $request)
    {

        // try {
            $categories = Category::all();
            $userId = auth()->id();
            //coge el usuario
            $cart = Cart::showCartByUserID($userId);
            // dd($cart);
            $productIds = Orders::decodeIds($cart);
            // dd($productIds);

            $shops = Shop::all();

            foreach ($shops as $shop) {
                $shopname = $shop->shopname;
            }

            $products = Product::all();

            // $productIds = Orders::decodeIds($cart->products);
            $productsByShop = array();
            $shopName = array();
            for ($i=0 ; $i< count($shops); $i++) {
                $shopName[$i] = $shops[$i]->shopname;
                for ($j=0 ; $j< count($productIds); $j++) {
                    $product = $products->where('id', $productIds[$j])->first();
                    if ($product && $product->shop_id == $shops[$i]->id) {

                        $productsByShop[$i][$j] = $product;
                    }
                }
            }
            // dd($productsByShop);
            return view('orders.index', [
                'categories' => $categories,
                'options_order' => Order::$order_array,
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
            $ordersDDBB = Orders::updateOrdersDB($productsArray);
            // dd($ordersDDBB);
        }

    }
}
