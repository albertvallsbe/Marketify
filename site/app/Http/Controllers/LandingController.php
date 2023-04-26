<?php

namespace App\Http\Controllers;

use App\Classes\Order;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        // Leemos el archivo JSON
        $json_string = file_get_contents('js/datos.json');

        // Decodificamos la cadena JSON en un array PHP
        $data = json_decode($json_string, true);
        $data = $data['variableCategories'];
        $activeTags = array();
        foreach($data as $key => $category) {
            $activeTags[$key]['name'] = self::getCategories($category['category']);
            $activeTags[$key]['products'] = self::getProducts($category['category'],$category['amount']);
            // array_push($activeTags, $tag['tag']);
        }
        
        //  dd($activeTags[0]['products'][0]['tag']);
        // dd($activeTags[0]);
        // Mostramos el contenido del array
        
        return view('landing.index', [
            'categories' => $categories,
            'options_order' => Order::$order_array,
            'activeTags' => $activeTags
        ]);
        
    }
    public function getProducts($category,$limit){
        return Product::query()
        ->join('category_product', 'category_product.product_id', '=', 'products.id')
        ->select('products.*')
        ->where('category_product.category_id', '=', $category)
        ->limit($limit)
        ->get()
        ;

    }
    public function getCategories($id){
        return Category::query()
        ->join('category_product', 'category_product.category_id', '=', 'categories.id')
        ->select('categories.*')
        ->where('category_product.category_id', '=', $id)
        ->first()
        ;
    }
   
}
