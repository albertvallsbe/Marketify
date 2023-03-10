<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index() {
        return view('cart.index');
    }
    
    public function add(Request $request) {
        $product_image = $request->product_image;
        $product_name = $request->product_name;
        $product_description = $request->product_description;
        $product_price = $request->product_price;
        echo $product_image;
        echo "<br>";
        echo $product_name;
        echo "<br>";
        echo $product_description;
        echo "<br>";
        echo $product_price;
        return view('cart.index');
    }
}
