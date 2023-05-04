<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Classes\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ErrorController extends Controller
{
    public function shop404() {
        $categories = Category::all();
        return view('errors.404-shop',['categories' => $categories,
        'options_order' => Order::$order_array]);
    }
    public function product404() {
        $categories = Category::all();
        return view('errors.404-product',['categories' => $categories,
        'options_order' => Order::$order_array]);
    }

    public function user404() {
        $categories = Category::all();
        return view('errors.404-user',['categories' => $categories,
        'options_order' => Order::$order_array]);
    }
    public function generic404() {
        $categories = Category::all();
        return view('errors.404-generic',['categories' => $categories,
        'options_order' => Order::$order_array]);
    }
}
