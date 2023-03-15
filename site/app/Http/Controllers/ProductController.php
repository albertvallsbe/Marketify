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
    public $search;

    public function filter(){

    }
    public function index(Request $request) {
        $this->search = $request->search;
        $order_request = $request->order ?? Order::$order;

        $filter = $request->filter;
        $categories = Category::selectCategory();
        // $products = Product::search($search,$filter, $this->order);
        if($this->search != ""){
            $products = Product::searchTagCategory($this->search,$filter, $order_request);
         }else {
            $products = Product::searchSpecific($filter);
         }
        
        // COMENTADA LA FUCION APPEND
        // $products->appends([
        //     'search' => $search,
        //     'order' => explode("_",$this->order)[0]."_".explode("_", $this->order)[1]
        // ]);
        
        
        $data = [
            'products' => $products,
            'search' => $this->search,
            'categories'=> $categories,
                 
        ];         

        $order_data = [
            'order_array' => Order::$order_array,
            'order' => Order::$order
        ];
        
        
        return view('product.index', $data, $order_data);
    }

    public function show($id){
        $product = Product::findOrFail($id);
        
        $categories = Category::selectCategory();
        $data = [
            'id' => $id,
            'product' => $product,
            'categories'=> $categories,
            'search' => $this->search
        ];
        $order_data = [
            'order_array' => Order::$order_array,
            'order' => Order::$order
        ];
       

        return view('product.show', $data, $order_data);
    }
  
}
