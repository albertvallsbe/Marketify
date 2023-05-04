<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop;
use App\Classes\Order;
use App\Models\Product;
use App\Models\Orders;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        try {
            $categories = Category::all();
            $ids = $request->query('id');
            if ($ids) {
                $ids = explode(',', $ids);
            }else{
                $ids = [];
            }
            $userId = auth()->id();
            $usersShop = Shop::findShopUserID($userId);
            if($usersShop){
                $shop = Shop::findOrFail($usersShop);
            }else{
                $shop = 0;
            }
            $error = false;
            $products = Product::showByIDs($ids);

            return view('orders.index', [
                'categories' => $categories,
                'options_order' => Order::$order_array,
                'products' => $products,
                'shop' => $shop
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
