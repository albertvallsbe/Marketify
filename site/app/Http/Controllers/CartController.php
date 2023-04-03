<?php

namespace App\Http\Controllers;

use App\Classes\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CartController extends Controller
{

    public function index(Request $request) {
    try{
        $categories = Category::all();
        $ids = $request->query('id');
        if ($ids) {
            $ids = explode(',', $ids);
        }else{
            $ids = [];
        }
        $products = Product::showByIDs($ids);
        return view('cart.index',['categories' => $categories,
        'options_order' => Order::$order_array,
        'products' => $products]);
    } catch (\Exception $e) {
        return view('error', ['message' => $e->getMessage()]);
    }
}

    // public function add(Request $request) {
    //     $localVariables = $request->all();
    //     $cart = json_decode($localVariables['cart']);
    //     $products = Product::showByIDs($cart);
    //     session(['request_cart' => $products]);
    // }    
}