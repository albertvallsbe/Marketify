<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\View\Components\Header;
use App\Classes\Order;

use App\Models\Category;
use App\Models\Product;

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
        // $id=auth()->user->id; Quan treballem amb usuaris, per recuperar id de l'usuari logat
        Product::create($request->all());

        return redirect()->route('products.index');
    }

    public function update(Request $request)
    {
        $product=Product::findOrFail($request->id);
        $product->update($request->all());

        return redirect()->route('products.edit', $product);
    }
}
