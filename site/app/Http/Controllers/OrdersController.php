<?php

namespace App\Http\Controllers;

use App\Classes\Order;

use App\Models\Cart;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        try {
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

    public function add(Request $request) {
        $productsArray = $request->input('cart');
        $userId = auth()->id();
        if($userId){
            Cart::updateCartDB($productsArray);
        }
    }
}
