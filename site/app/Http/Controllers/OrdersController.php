<?php

namespace App\Http\Controllers;

use App\Models\Cart;

use App\Models\Shop;
use App\Classes\Order;
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

            $userId = auth()->id();
            if ($userId) {
                $arrayCart = Cart::showCartByUserID($userId);
            } else {
                $arrayCart = "[]";
            };

            $usersShop = Shop::findShopUserID($userId);
            if($usersShop){
                $shop = Shop::findOrFail($usersShop);
            }else{
                $shop = 0;
            }



            return view('orders.index', [
                'categories' => $categories,
                'options_order' => Order::$order_array,
                // 'cartProducts' => $cartProducts
                'carts' => $carts,
                'shop' => $shop,
                'options_order' => Order::$order_array,
                'cartProducts' => $arrayCart,
                'productIds' => $productIds,
                // 'arrayProductsId' => $arrayProducts
                // 'shopname' => $shopName,
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

    public function decodeIds($string)
    {
        // Decodificar la cadena JSON
        $decodedString = json_decode($string);

        // Comprobar si la decodificación ha sido correcta
        if (is_array($decodedString)) {
            // Convertir los valores en el array en enteros y retornarlos
            return array_map('intval', $decodedString);
        } else {
            // En caso de que la decodificación fallara, retornar un array vacío
            return [];
        }
    }

    public function show($id)
    {
        $order = Orders::find($id);
        $decodedIds = Orders::decodeIds($order->products);
        return view('orders.show', compact('order', 'decodedIds'));
    }
}
