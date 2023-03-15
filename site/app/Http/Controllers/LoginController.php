<?php

namespace App\Http\Controllers;

use App\Classes\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index() {

        $categories = Category::selectCategory();
        $order_data = [
            'order_array' => Order::$order_array,
            'order' => Order::$order,
            'categories'=> $categories,
            'search' => ''
        ];
        
        return view('login.index', $order_data);
    }
}
