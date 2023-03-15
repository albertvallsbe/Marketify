<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Category;

class ProductController extends Controller
{
    private $order = 'name_asc';

    private $order_array = [
        'name_asc' => 'Name Ascendent',
        'name_desc' => 'Name Descendent',
        'price_asc' => 'Price Ascendent',
        'price_desc' => 'Price Descendent'
    ];

    public function filter(){

    }
    public function index(Request $request) {
        $search = $request->search;
        $this->order = $request->order ?? $this->order;
        $filter = $request->filter;
        $categories = Category::selectCategory();
        // $products = Product::search($search,$filter, $this->order);
        if($search != ""){
            $products = Product::searchTagCategory($search,$filter, $this->order);
            echo "hola " . $search  ;
         }else {
            $products = Product::searchSpecific($filter);
            echo $filter;
         }
        
        // COMENTADA LA FUCION APPEND
        // $products->appends([
        //     'search' => $search,
        //     'order' => explode("_",$this->order)[0]."_".explode("_", $this->order)[1]
        // ]);
        
        
        $data = [
            'products' => $products,
            'search' => $search,
            'categories'=> $categories,
                 
                 
            
            
        ];
       

        $order_data = [
            'order_array' => $this->order_array,
            'order' => $this->order
        ];
        
        
        return view('product.index', $data, $order_data);
    }

    public function show($id){
        $product = Product::findOrFail($id);
        
        $data = [
            'id' => $id,
            'product' => $product
        ];
        $order_data = [
            'order_array' => $this->order_array,
            'order' => $this->order
        ];
       

        return view('product.show', $data, $order_data);
    }
  
}
