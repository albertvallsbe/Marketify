<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $order = 'name_asc';

    private $order_array = [
        'name_asc' => 'Name Ascendent',
        'name_desc' => 'Name Descendent',
        'price_asc' => 'Price Ascendent',
        'price_desc' => 'Price Descendent'
    ];

    public function index(Request $request) {
        $search = $request->search;
        $this->order = $request->order ?? $this->order;
    
        $products = Product::search($search, $this->order);
        $products->appends([
            'search' => $search,
            'order' => explode("_",$this->order)[0]."_".explode("_", $this->order)[1]
        ]);
    
        $data = [
            'products' => $products,
            'search' => $search,

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
