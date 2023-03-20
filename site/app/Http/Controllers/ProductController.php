<?php

namespace App\Http\Controllers;


use App\Classes\Order;
use Illuminate\Http\Request;

// use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller {
    public function index(Request $request) {
        $order_request = $request->order ?? "name_asc";
        
        session(['request_order' => $order_request]);
        session(['request_search' => $request->search]);
        session(['request_categories' => $request->filter]);
        session(['options_order' => Order::$order_array]);
        session(['categories' => Category::all()]);
        
        if($request->session()->has('request_categories') == ""){
            $products = Product::searchAll(session('request_search'), session('request_order'));
        }else {
            $products = Product::searchSpecific(session('request_search'), session('request_categories'), session('request_order'));
        }

        $products->appends([
            'filter' => session('request_categories'),
            'search' => session('request_search'),
            'order' => session('request_order')
        ]);

        return view('product.index', ['products' => $products]);
    }

    public function show($id){
        $product = Product::findOrFail($id);
        return view('product.show', ['product' => $product]);
    }


    public function store(Request $request) {
        Product::create($request->all());
        return redirect('/');
    }
}