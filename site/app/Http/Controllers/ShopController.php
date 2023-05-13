<?php
namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Classes\HeaderVariables;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\ValidationMessages;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShopController extends Controller {
    public function index() {
        $categories = Category::all();
        return view('shop.index',['categories' => $categories,
        'options_order' => HeaderVariables::$order_array]);
    }

    public function show($url) {
        try {
            $shop = Shop::showByURL($url);
            $products = Product::productsShop($shop->id, $shop->order);
            $categories = Category::all();

        if(auth()->user()) {
            $userID = Auth::user()->id;
            $usersShop = Shop::findShopUserID($userID);
        }else{
            $usersShop = 0;
        }
        $header_color = Shop::findHeaderShopColor($shop->id);
        $background_color = Shop::findBackGroundShopColor($shop->id);
        
            return view('shop.show', ['shop' => $shop,
            'categories' => $categories,
            'options_order' => HeaderVariables::$order_array,
            'products' => $products,
            'usersShop' => $usersShop,
            'header_color' => $header_color,
            'background_color' => $background_color]);
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
                $products = Product::productsShop($shopID, $shop->order);
                $categories = Category::all();

                $header_color = Shop::findHeaderShopColor($shopID);
                $background_color = Shop::findBackGroundShopColor($shop->id);
                return view('shop.admin', [
                    'products' => $products,
                    'shop' => $shop,
                    'categories' => $categories,
                    'options_order' => HeaderVariables::$order_array,
                    'header_color' => $header_color,
                    'background_color' => $background_color
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
            'shopname' => 'required|string|regex:/[a-zA-Z0-9\s]+/|unique:shops',
            'username'=>'required|string|unique:shops',
            'nif' => 'required|string|alpha_num|unique:shops',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'header_color' => ['required', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'background_color' => ['required', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
        ], ValidationMessages::shopValidationMessages());
        if(Shop::generateURL($validatedData['shopname']) == ""){
            return redirect()->back()->withErrors(['shopname' => 'The shopname has already been taken.']);
        }

        if($request->hasFile('image')) {
            $image = $validatedData['image'];
            $name = uniqid('logo_') . '.' . $image->extension();
            $path = 'images/logo/';
            $image->move($path, $name);
            $logoPath = $path . $name;
        }
        $id = Auth::user()->id;
        $store_name = $validatedData['shopname'];
        $username = $validatedData['username'];
        $nif = $validatedData['nif'];
        $header_color = $validatedData['header_color'];
        $background_color = $validatedData['background_color'];
        Shop::create([
            'shopname' => $store_name,
            'username'=>$username,
            'nif' => $nif,
            'logo' => $logoPath ?? 'images/logos/default-logo.png',
            'user_id' => $id,
            'url' => Shop::generateURL($validatedData['shopname']),
            'header_color' => $header_color,
            'background_color' => $background_color
        ]);
        Shop::makeUsercustomer($id);
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
                'options_order' => HeaderVariables::$order_array,
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
                'shopname' => [
                    'required',
                    'string',
                    'regex:/[a-zA-Z0-9\s]+/',
                    Rule::unique('shops')->ignore($shop->id),
                ],
                'username' => [
                    'required',
                    'string',
                    Rule::unique('shops')->ignore($shop->id),
                ],
                'nif' => [
                    'required',
                    'string',
                    'alpha_num',
                    Rule::unique('shops')->ignore($shop->id),
                ],
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'header_color' => ['required', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
                'background_color' => ['required', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
                'order' => 'required'
            ], ValidationMessages::shopValidationMessages());
            if(Shop::generateURL($validatedData['shopname']) == ""){
                return redirect()->back()->withErrors(['shopname' => 'The shopname has already been taken.']);
            }
            if($request->hasFile('image')) {
                $image = $validatedData['image'];
                $name = uniqid('logo_') . '.' . $image->extension();
                $path = 'images/logos/';
                $image->move($path, $name);
                $logoPath = $path . $name;
            }
            $shop->update([
                'shopname' => $validatedData['shopname'],
                'username'=>$validatedData['username'],
                'url' => Shop::generateURL($validatedData['shopname']),
                'nif' => $validatedData['nif'],
                'logo' => $logoPath ?? $shop->logo,
                'header_color' => $validatedData['header_color'],
                'order' => $validatedData['order'],
                'background_color' => $validatedData['background_color'],
            ]);
            session()->flash('status', "Shop edited successfully.");
            return redirect()->route('shop.admin');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('shop.index');
        }
    }
}
