<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Classes\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    public function create(Request $request) {
    
        $categories = Category::all();
        
        return view('shop.create',['categories' => $categories,
        'options_order' => Order::$order_array]);
    }
}
