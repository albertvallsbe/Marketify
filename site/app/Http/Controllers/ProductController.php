<?php

namespace App\Http\Controllers;


use App\Classes\Order;
use App\Models\Product;

use App\Models\Category;
use App\View\Components\Header;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $header = new Header($request);

        $categories = Category::all();

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

        return view('product.index', [
            'products' => $products,
            'categories' => $categories,
            'options_order' => Order::$order_array]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('product.show', ['product' => $product,
        'categories' => $categories,
        'options_order' => Order::$order_array]);
    }

    public function store(Request $request)
    {
        $request->user()->products()->create($request->all());

        return redirect()->route('products.index');
    }
}
