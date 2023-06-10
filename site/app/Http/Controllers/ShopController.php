<?php
namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\HeaderVariables;
use App\Helpers\ValidationMessages;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShopController extends Controller {
    //Vista principal de la tienda
    public function index() {
        try {
            if (auth()->id()) {
                $categories = Category::all();
                Log::channel('marketify')->info('shop.index view loaded');
                return view('shop.index',['categories' => $categories,
                'options_order' => HeaderVariables::$order_array]);
            } else {
                return redirect()->route('login.index'); 
            }
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred showing shop view: '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }

    //Vista selectiva de la tienda
    public function show($url) {
        try {
            // Hacemos la petición a la api
            $client = new Client();
            $response = $client->get(env('API_IP').'api/images/view/all', [
                'verify' => false
            ]);
            // Comprobamos que ha recibido las imágenes de manera correcta
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                $paths = Arr::pluck($data, 'path','product_id');
            } else {
                throw new \Exception('Error retrieving images from the API');
            }

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

            Log::channel('marketify')->info('shop.show view loaded');
                return view('shop.show', ['shop' => $shop,
                'categories' => $categories,
                'options_order' => HeaderVariables::$order_array,
                'products' => $products,
                'usersShop' => $usersShop,
                'paths'=>$paths,
                'header_color' => $header_color,
                'background_color' => $background_color]);
        } catch (ModelNotFoundException $e) {
            Log::channel('marketify')->error('An error occurred showing product shop view: '.$e->getMessage());
            return redirect()->route('shop.404');
        }
    }

    //Vista de administrador de la tienda
    public function admin() {
        try {
            if(auth()->user()) {
                $id = Auth::user()->id;
                $shopID = Shop::findShopUserID($id);
                try {           
                        // Hacemos la petición a la api
                    $client = new Client();
                    $response = $client->get(env('API_IP').'api/images/view/all', [
                        'verify' => false
                    ]);
                    // Comprobamos que ha recibido las imágenes de manera correcta
                    if ($response->getStatusCode() === 200) {
                        $data = json_decode($response->getBody(), true);
                        $paths = Arr::pluck($data, 'path','product_id');
                    } else {
                        throw new \Exception('Error retrieving images from the API');
                    }

                    $shop = Shop::findOrFail($shopID);
                    $products = Product::productsShop($shopID, $shop->order);
                    $categories = Category::all();

                    $header_color = Shop::findHeaderShopColor($shopID);
                    $background_color = Shop::findBackGroundShopColor($shop->id);

                    Log::channel('marketify')->info('shop.admin view loaded');
                    return view('shop.admin', [
                        'products' => $products,
                        'shop' => $shop,
                        'categories' => $categories,
                        'options_order' => HeaderVariables::$order_array,
                        'paths'=>$paths,
                        'header_color' => $header_color,
                        'background_color' => $background_color
                    ]);
                } catch (ModelNotFoundException $e) {
                    Log::channel('marketify')->info('Error creating shop');
                    return redirect()->route('shop.index');
                }
            }else{
                Log::channel('marketify')->info('User not logged in shop.admin, redirect to login instead');
                return redirect()->route('login.index');
            }
        } catch (ModelNotFoundException $e) {
            Log::channel('marketify')->error('An error occurred showing admin shop view: '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }

    //Petición post de creación de tienda
    public function create(Request $request) {
        try {
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
            Log::channel('marketify')->info("Shop has been created");
            return redirect()->route('shop.admin');
        } catch (ModelNotFoundException $e) {
            Log::channel('marketify')->error('An error occurred creatig a shop: '.$e->getMessage());
        }
    }

    //Vista para editar tienda
    public function edit() {
        try {
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
                Log::channel('marketify')->info('User not logged in shop.edit, redirect to login instead');
                return redirect()->route('login.index');
            }
        } catch (ModelNotFoundException $e) {
            Log::channel('marketify')->error('An error occurred showing edit shop: '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }


    //Petición post de edición de tienda
    public function update(Request $request) {
        try {
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
                
                Log::channel('marketify')->info("Shop #$shop->id has been updated");
                session()->flash('status', "Shop edited successfully.");
                return redirect()->route('shop.admin');
            } catch (ModelNotFoundException $e) {
                return redirect()->route('shop.index');
            }
        } catch (ModelNotFoundException $e) {
            Log::channel('marketify')->error('An error occurred with edit shop: '.$e->getMessage());
        }
    }
}
