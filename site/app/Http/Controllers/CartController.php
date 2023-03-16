<?php

namespace App\Http\Controllers;

use App\Classes\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{

    public function index() {

        $categories = Category::all();
        $order_data = [
            'order_array' => Order::$order_array,
            'order' => "name_asc",
            'categories'=> $categories,
            'search' => '',
            'filter'=> ""
        ];

        return view('cart.index', $order_data);
    }

    public function add(Request $request) {
    }
}
