<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop;
use App\Classes\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index(Request $request)
    {

        try {

            $categories = Category::all();
            $ids = $request->query('id');
            if ($ids) {
                $ids = explode(',', $ids);
            } else {
                $ids = [];
            }
            $userId = auth()->id();
            $usersShop = Shop::findShopUserID($userId);
            if ($usersShop) {
                $shop = Shop::findOrFail($usersShop);
            } else {
                $shop = 0;
            }
            $error = false;
            $products = Product::showByIDs($ids);

            Log::channel('marketify')->info('The cart view has been loaded successfully.');

            return view('cart.index', [
                'categories' => $categories,
                'options_order' => Order::$order_array,
                'products' => $products,
                'shop' => $shop,
                'error' => $error
            ]);
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred while loading the home view: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the home view.');
        }
    }

    public function add(Request $request)
    {

        try {
            $productsArray = $request->input('cart');
            $userId = auth()->id();
            if ($userId) {
                Cart::updateCartDB($productsArray);
            }
            Log::channel('marketify')->info('add has been loaded successfully ');

        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred while loading the home view: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the home view.');
        }
    }
}
