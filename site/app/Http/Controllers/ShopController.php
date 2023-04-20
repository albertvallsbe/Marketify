<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Classes\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            $categories = Category::all();
            return view('shop.show', ['shop' => $shop,
            'categories' => $categories,
            'options_order' => Order::$order_array]);
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
            'name' => 'required|string',
            'nif' => 'required|string',
        ]);
        $id = Auth::user()->id;
        $name = $request->input('name');
        $nif = $request->input('nif');

        Shop::create([
            'name' => $name,
            'nif' => $nif,
            'user_id' => $id,
        ]);
        Shop::makeUserShopper($id);
        return redirect()->route('shop.edit');
    }
}