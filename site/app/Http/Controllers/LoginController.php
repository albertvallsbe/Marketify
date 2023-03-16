<?php

namespace App\Http\Controllers;

use App\Classes\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function index() {

        $categories = Category::selectCategory();
        $order_data = [
            'order_array' => Order::$order_array,
            'order' => "name_asc",
            'categories'=> $categories,
            'search' => '',
            'filter'=> ""
        ];
        
        return view('login.index', $order_data);
    }
}
