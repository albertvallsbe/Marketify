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
        $products = Product::where('name','LIKE','%'.$search.'%')
        // ->orWhere('description','LIKE','%'.$search.'%')
        ->simplePaginate(8);
        // ->paginate(4);

        $data = [
            'products'=>$products,
            'search'=>$search,
        ];
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
