<?php

namespace App\Http\Controllers;

use App\Classes\Order;
use App\Models\Category;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index (){
        $categories = Category::all();
        return view('landing.index',['categories' => $categories,
        'options_order' => Order::$order_array]);
    }
}
