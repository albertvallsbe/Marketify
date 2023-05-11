<?php

namespace App\Http\Controllers;

use App\Classes\HeaderVariables;

use App\Models\Cart;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index(Request $request)
    {
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
        // dd($ids);
        return view('cart.index', [
            'categories' => $categories,
            'options_order' => HeaderVariables::$order_array,
            'products' => $products,
            'shop' => $shop,
            'error'=> $error
        ]);
    }

    public function add(Request $request) {
        $productsArray = $request->input('cart');
        $userId = auth()->id();
        if($userId){
            Cart::updateCartDB($productsArray);
        }
    }
}
