<?php

namespace App\Http\Controllers;

use App\Classes\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class LoginController extends Controller
{
    public function index() {
        $categories = Category::all();

        return view('login.index',['categories' => $categories,
        'options_order' => Order::$order_array]);
    }
}
