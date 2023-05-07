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
        try {
            $categories = Category::all();
            $carts = Cart::all();

            foreach ($carts as $cart) {
                $productIds = Orders::decodeIds($cart->products);
            }

            $shops = Shop::all();

            foreach ($shops as $shop) {
                $shopname = $shop->shopname;
            }

            $products = Product::all();

            // $productIds = Orders::decodeIds($cart->products);
            $productsByShop = [];
            foreach ($productIds as $productId) {
                $product = $products->where('id', $productId)->first();
                if ($product && $product->shop_id == $shop->id) {
                    $productsByShop[] = $product;
                }
            }

            // $userId = auth()->id();
            // if ($userId) {
            //     $arrayCart = Cart::showCartByUserID($userId);
            // } else {
            //     $arrayCart = "[]";
            // };

            // $usersShop = Shop::findShopUserID($userId);
            // if($usersShop){
            //     $shop = Shop::findOrFail($usersShop);
            // }else{
            //     $shop = 0;
            // }



            return view('orders.index', [
                'categories' => $categories,
                'options_order' => Order::$order_array,
                'carts' => $carts,
                'products' => $products,
                'productIds' => $productIds,
                'shopname' => $shopname,
                'shops' => $shops,
                'productsByShop' => $productsByShop
                // 'cartProducts' => $cartProducts
                // 'shop' => $shop,
                // 'cartProducts' => $arrayCart,
                // 'arrayProductsId' => $arrayProducts
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

//  @foreach ($shops as $shop)
//     <div class="cart-product">
//         <h4>Shop: {{ $shop->shopname }}</h4>
//         @foreach ($products as $product)
//             @foreach ($productIds as $productId)
//             {{-- <p>{{ $productId }} {{$shop->shopname}}</p> --}}
//                 @if ($product->shop_id === $shop->id )
//                     <p>{{ $productId }} {{$shop->shopname}}</p>
//                 @endif
//             @endforeach

//             {{-- <p>Shop: {{ $shopname}}</p> --}}
//             {{-- <h4>{{ $cart->id }}</h4> --}}
//             {{-- <p>Products: {{ $cart->products }}</p> --}}
//             {{-- <p>Shop Name: {{ $cart->shopname }}</p> --}}
//             {{-- <p>Shop: {{ $shop->username}}</p> --}}

//         @endforeach
//     </div>
// @endforeach
