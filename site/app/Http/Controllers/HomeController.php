<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request) {
        $search = $request->search;
        $order = $request->order ?? 'name_asc';

        $products = Product::where('name','LIKE','%'.$search.'%')
        ->orderBy(explode("_", $order)[0], explode("_", $order)[1])
        ->simplePaginate(8);
        
        
        $order_array = [
            'name_asc' => 'Name Ascendent',
            'name_desc' => 'Name Descendent',
            'price_asc' => 'Price Ascendent',
            'price_desc' => 'Price Descendent'
        ];
        
        $data = [
            'products'=>$products,
            'search'=>$search,
            'order_array'=>$order_array,
            'order' => $order,
        ];
        // return view('home.index', $data);

        return view('home.index', $data);
    }

    public function show($id){
        $product = Product::findOrFail($id);
        return view('product.show', ['id' => $id], ['product' => $product]);
    }

    public function login() {

        return view('home.login');
    }
}
