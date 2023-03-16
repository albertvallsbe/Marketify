<?php

namespace App\Http\Controllers;


use App\Classes\Order;
use App\Models\Product;
use Illuminate\Http\Request;

// use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request) {
        $search = $request->search;
        $order_request = $request->order ?? "name_asc";
        $filter = $request->filter;
        $categories = Category::all();
        if($filter == ""){
            $products = Product::searchAll($search, $order_request);
         }else {
            $products = Product::searchSpecific($search, $filter, $order_request);
         }
        
        $products->appends([
            'filter' => $filter,
            'search' => $search,
            'order' => explode("_",$order_request)[0]."_".explode("_", $order_request)[1]
        ]);
        
        
        $data = [
            'products' => $products,
            'search' => $search,
            'categories'=> $categories,
            'filter'=> $filter,
            'order_array' => Order::$order_array,
            'order' => $order_request
                 
        ];
        
        
        return view('product.index', $data);
    }

    public function show($id){
        $product = Product::findOrFail($id);
        
        $categories = Category::selectCategory();
        $data = [
            'id' => $id,
            'product' => $product,
            'categories'=> $categories,
        ];
        $order_data = [
            'order_array' => Order::$order_array,
            'order' => "name_asc",
            'filter'=> "",
            'search' => ""
        ];
       

        return view('product.show', $data, $order_data);
    }
  
}
