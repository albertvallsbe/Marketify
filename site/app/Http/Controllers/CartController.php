<?php

namespace App\Http\Controllers;

use App\Models\Cart;

use App\Models\Shop;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Classes\HeaderVariables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CartController extends Controller {
    /**
     * Vista principal del carrito
     */
    public function index(Request $request) {
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
            
            /**
             * 'id' es la QueryString de la URL de cart
             */
            $ids = $request->query('id');
            if ($ids) {
                $ids = explode(',', $ids);
            }else{
                $ids = [];
            }

            /**
             *Comprobamos la ID del usuario y si le pertenece una tienda, para hacer la comprobación en el carrito.
             */
            $userId = auth()->id();
            $usersShop = Shop::findShopUserID($userId);
            if($usersShop){
                $shop = Shop::findOrFail($usersShop);
            }else{
                $shop = 0;
            }
            $error = false;
            $products = Product::showByIDs($ids);

            $categories = Category::all();
            Log::channel('marketify')->info('cart.index view loaded');
            return view('cart.index', [
                'categories' => $categories,
                'options_order' => HeaderVariables::$order_array,
                'products' => $products,
                'paths'=>$paths,
                'shop' => $shop,
                'error'=> $error
            ]);
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred showing cart view: '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }

    /**
     * Petición POST para actualizar el carrito en la base de datos
     */
    public function add(Request $request) {
        try {
            $productsArray = $request->input('cart');
            $userId = auth()->id();
            if($userId){
                Cart::updateCartDB($productsArray);
            }
            Log::channel('marketify')->info('Cart in DBB updated');
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred updating cart in database: '.$e->getMessage());
        }
    }
}
