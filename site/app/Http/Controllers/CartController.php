<?php

namespace App\Http\Controllers;

use App\Classes\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class CartController extends Controller
{

    public function index() {
        $categories = Category::all();
        return view('cart.index',['categories' => $categories,
        'options_order' => Order::$order_array]);
    }
    public function add(Request $request) {
    }
}
