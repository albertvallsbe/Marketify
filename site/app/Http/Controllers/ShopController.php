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
use App\Helpers\ValidationMessages;

class ShopController extends Controller {
    public function index() {
        $categories = Category::all();
        return view('shop.index',['categories' => $categories,
        'options_order' => Order::$order_array]);
    }
    
    public function show($url) {
        try {
            $shop = Shop::findShopByURL($url);
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
    
    public function admin() {
        if(auth()->user()) {
            $id = Auth::user()->id;
            $shopID = Shop::findShopUserID($id);
            try {
                $shop = Shop::findOrFail($shopID);
                $products = Product::productsShop($shopID);
                $categories = Category::all();

                return view('shop.admin', [
                    'products' => $products,
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
        $validatedData = $request->validate([
            'storename' => 'required|string',
            'username'=>'required|string',
            'nif' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], ValidationMessages::shopValidationMessages());

        if($request->hasFile('image')) {
            $image = $validatedData['image'];
            $name = uniqid('logo_') . '.' . $image->extension();
            $path = 'images/logo/';
            $image->move($path, $name);
            $logoPath = $path . $name;
        }
        $id = Auth::user()->id;
        $store_name = $validatedData['storename'];
        $username = $validatedData['username'];
        $nif = $validatedData['nif'];
        Shop::create([
            'shopname' => $store_name,
            'username'=>$username,
            'nif' => $nif,
            'logo' => $logoPath ?? null,
            'user_id' => $id,
        ]);
        Shop::makeUserShopper($id);
        return redirect()->route('shop.admin');
    }

    public function edit() {
        if(auth()->user()) {
            $id = Auth::user()->id;
            $shopID = Shop::findShopUserID($id);
            try {
                $shop = Shop::findOrFail($shopID);
                $categories = Category::all();
                return view('shop.edit', ['categories' => $categories,
                'options_order' => Order::$order_array,
                'shop' => $shop]);
            } catch (ModelNotFoundException $e) {
                return redirect()->route('shop.index');
                }
        }else {
            return redirect()->route('login.index');
        }
    }


    public function update(Request $request) {  
        $id = Auth::user()->id;
        $shopID = Shop::findShopUserID($id);
        try {
            $shop = Shop::findOrFail($shopID);
            $validatedData = $request->validate([
                'storename' => 'required|string',
                'username'=>'required|string',
                'nif' => 'required|string',
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ], ValidationMessages::shopValidationMessages());
            
            if($request->hasFile('image')) {
                $image = $validatedData['image'];
                $name = uniqid('logo_') . '.' . $image->extension();
                $path = 'images/logos/';
                $image->move($path, $name);
                $logoPath = $path . $name;
            }
            $shop->update([
                'shopname' => $validatedData['storename'],
                'username'=>$validatedData['username'],
                'url' => Shop::generateURL($validatedData['storename']),
                'nif' => $validatedData['nif'],
                'logo' => $logoPath ?? $shop->logo,
            ]);
            session()->flash('status', "Shop edited successfully.");
            return redirect()->route('shop.admin');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('shop.index');
        }
    }
}