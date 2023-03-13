<?php

namespace App\Http\Controllers;

use App\Classes\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    
    public function index() {

        $order_data = [
            'order_array' => Order::$order_array,
            'order' => Order::$order
        ];
        
        return view('cart.index', $order_data);
    }

    public function add(Request $request) {
    }
}
