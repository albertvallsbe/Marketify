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
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Crear las orders del carrito de servidor
     */
    public function index(Request $request)
    {
        try {
            if ($request->isMethod('get')) {
                $categories = Category::all();
                $shops = Shop::all();
                $products = Product::all();

                $shopName = array();
                for ($i=0 ; $i< count($shops); $i++) {
                    $shopName[$i] = $shops[$i]->shopname;
                }
                $productsByShop = Order::findShopAndCartProducts();

                Log::channel('marketify')->info('order.index view loaded');
                return view('order.index', [
                    'categories' => $categories,
                    'options_order' => HeaderVariables::$order_array,
                    'products' => $products,
                    'shops' => $shops,
                    'productsByShop' => $productsByShop,
                    'shopName' => $shopName,
                ]);
            } else {
                return redirect()->route('landing.index');
            }
        } catch (\Exception $e) {
                Log::channel('marketify')->error('An error occurred showing order view: '.$e->getMessage());
                return redirect()->back()->with('error', 'An error occurred in OrderController.');
        }
    }

    /**
     * Añadir a la base de datos una order por cada vendedor del carrito del usuario
     */
    public function add() {
        try{
            $productsByShop = Order::findShopAndCartProducts();
            $shops = Shop::all();
            $shopName = array();
            $productSold = false;
            for ($i=0 ; $i< count($shops); $i++) {
                $shopName[$i] = $shops[$i]->id;
            }
            foreach ($productsByShop as $key => $shopByProduct) {
                $shopId = $shops[$key]->id;

            foreach ($shopByProduct as $key => $products) {
                if ($products->status == 'sold') {
                    $productSold = true;
                }
            }
        }
            if (!$productSold) {
                foreach ($productsByShop as $key => $shopByProduct) {
                    $shopId = $shops[$key]->id;

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'shop_id' => $shopId
                ]);
                Log::channel('marketify')->info("Order #$order->id of $order->user_id has created");
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
            /**
             * Borrar el contenido del campo 'products' en la tabla 'carts' del usuario
             */
            Cart::where('user_id', auth()->id())->delete();
            return redirect()->route('chat.index');
            } else{
                Log::channel('marketify')->error("Order could not be created. Product with status 'sold' detected.");
                return redirect()->route('order.error');
            }
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred creating orders: '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred in OrderController.');
        }
    }

    /**
     * Error al crear la order
     */
    public function error(Request $request)
    {
        try {
            $categories = Category::all();
            return view('order.error', [
                'categories' => $categories,
                'options_order' => HeaderVariables::$order_array
            ]);
        } catch (\Exception $e) {
                Log::channel('marketify')->error('An error occurred showing order fail view: '.$e->getMessage());
                return redirect()->back()->with('error', 'An error occurred in OrderController.');
        }
    }

}
