<?php

namespace App\Http\Controllers;

use DB;
use App\Classes\Order;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $search;

    public function index(Request $request) {
        $this->search = $request->search;
        $order_request = $request->order ?? Order::$order;
    
        $products = Product::search($this->search, $order_request);
        $products->appends([
            'search' => $this->search,
            'order' => explode("_", $order_request)[0]."_".explode("_", $order_request)[1]
        ]);
    
        $data = [
            'products' => $products,
            'search' => $this->search
        ];
        $order_data = [
            'order_array' => Order::$order_array,
            'order' => Order::$order
        ];
        return view('product.index', $data, $order_data);
    }

    public function show($id){
        $product = Product::findOrFail($id);
        
        $data = [
            'id' => $id,
            'product' => $product,
            'search' => $this->search
        ];
        $order_data = [
            'order_array' => Order::$order_array,
            'order' => Order::$order
        ];

        return view('product.show', $data, $order_data);
    }
}
