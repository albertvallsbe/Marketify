<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Classes\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShopController extends Controller
{
    public function index() {
        $categories = Category::all();
        return view('shop.index',['categories' => $categories,
        'options_order' => Order::$order_array]);
    }
    
    public function show($id){
        try {
            $shop = Shop::findOrFail($id);
            
            $products = Product::productsShop($shop->id);
            $categories = Category::all();
            return view('shop.show', ['shop' => $shop,
            'categories' => $categories,
            'options_order' => Order::$order_array,
            'products' => $products]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('shop.404');
        }
    }
    
    public function edit(){
        if(auth()->user()){
            $id = Auth::user()->id;
            $shopID = Shop::findShopUserID($id);
            try {
                $shop = Shop::findOrFail($shopID);
                $categories = Category::all();
                return view('shop.edit', [
                    'shop' => $shop,
                    'categories' => $categories,
                    'options_order' => Order::$order_array
                ]);
            } catch (ModelNotFoundException $e) {
                return redirect()->route('shop.index');
            }
        }else{
            return redirect()->route('login.index');
        }
    }
    
    public function create(Request $request) {
        $request->validate([
            'store_name' => 'required|string',
            'username'=>'required|string',
            'nif' => 'required|string',
        ]);
        $id = Auth::user()->id;
        $store_name = $request->input('store_name');
        $username = $request->input('username');
        $nif = $request->input('nif');

        Shop::create([
            'shopname' => $store_name,
            'username'=>$username,
            'nif' => $nif,
            'user_id' => $id,
        ]);
        Shop::makeUserShopper($id);
        return redirect()->route('shop.edit');
    }
}