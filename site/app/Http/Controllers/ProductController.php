<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Category;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\View\Components\Header;
use App\Classes\HeaderVariables;
use App\Models\Category_Product;
use Illuminate\Http\UploadedFile;
use App\Helpers\ValidationMessages;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    /**
     * Vista principal de los productos
     */
    public function index(Request $request)
    {
        try {
            $header = new Header($request);

            //Filtrar según categorias o orden
            $categories = Category::all();
            if ($request->session()->has('request_categories') == "") {
                $products = Product::searchAll(session('request_search'), session('request_order'));
            } else {
                $products = Product::searchSpecific(session('request_search'), session('request_categories'), session('request_order'));
            }
            $products->appends([
                'filter' => session('request_categories'),
                'search' => session('request_search'),
                'order' => session('request_order')
            ]);

            //Guardar el carrito en una cookie para utilizarlo en el localStorage de JavaScript
            $userId = auth()->id();
            if ($userId) {
                $arrayCart = Cart::showCartByUserID($userId);
                $notificationCount = Notification::unreadCountForCurrentUser();
            } else {
                $arrayCart = "[]";
                $notificationCount = 0;
            }
            setcookie("arrayCart", $arrayCart);

            // Hacemos la petición a la api
            $client = new Client();

            $response = $client->get(env('API_IP').'api/images/view/all', [
                'verify' => false
            ]);
            $data = json_decode($response->getBody(), true);
            
            $paths = [];
            foreach ($data as $ruta ) {
                array_push($paths,$ruta);          
            }
            //Comprobamos la ID del usuario y si le pertenece una tienda, para comprobar si le pertenece el producto mostrado.
            $usersShop = Shop::findShopUserID($userId);
            if ($usersShop) {
                $shop = Shop::findOrFail($usersShop);
            } else {
                $shop = 0;
            }
            Log::channel('marketify')->info('product.index view loaded');
            return view('product.index', [
                'products' => $products,
                'categories' => $categories,
                'options_order' => HeaderVariables::$order_array,
                'shop' => $shop,
                'paths'=>$paths,
                'notificationCount' => $notificationCount
            ]);
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred showing product view: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }

    /**
     * Vista detalle del producto
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            $productShop = $product->shop;
            $categories = Category::all();

            $client = new Client();
            $response = $client->request('GET', env('API_IP').'api/images/view/'.$id, [
                'verify' => false
            ]);
            if (true) {
                $images = json_decode($response->getBody(), true);

                //Comprobamos la ID del usuario y si le pertenece una tienda, para comprobar si le pertenece el producto mostrado.
                $userId = auth()->id();
                $usersShop = Shop::findShopUserID($userId);
                if (!$usersShop) {
                    $shop = 0;
                    
                    if ($product->status != "hidden") {
                        $category_id = Category::findCategoryOfProduct($product->id);
                        $categoryName = Category::findCategoryName($category_id);
                
                        Log::channel('marketify')->info('product.show view loaded');
                        return view('product.show', [
                            'product' => $product,
                            'categories' => $categories,
                            'options_order' => HeaderVariables::$order_array,
                            'shop' => $shop,
                            'productShop' => $productShop,
                            'images'=> $images,
                            'categoryname' => $categoryName
                        ]);
                    } else {
                        return redirect()->route('product.index');
                    }
                }else{
                    $shop = Shop::findOrFail($usersShop);
                    if ($product->status != "hidden" ||$product->shop_id == $shop->id) {
                        $category_id = Category::findCategoryOfProduct($product->id);
                        $categoryName = Category::findCategoryName($category_id);
                        
                        Log::channel('marketify')->info('product.show view loaded');
                        return view('product.show', [
                            'product' => $product,
                            'categories' => $categories,
                            'options_order' => HeaderVariables::$order_array,
                            'shop' => $shop,
                            'productShop' => $productShop,
                            'images'=> $images,
                            'categoryname' => $categoryName
                        ]);
                    } else {
                        return redirect()->route('product.index');
                    }
                }
            } else {
                    abort(404);
            }
        } catch (ModelNotFoundException $e) {
            Log::channel('marketify')->error('An error occurred showing product show view: '.$e->getMessage());
            return redirect()->route('product.404');
        }
    }

    /**
     * Vista para creación de producto
     */
    public function create()
    {
        try {
            if (auth()->id()) {

                $user = User::findOrFail(auth()->id());
                if ($user->role == 'seller') {
                    $categories = Category::all();
                    Log::channel('marketify')->info('product.create view loaded');
                    return view('product.create', [
                        'categories' => $categories,
                        'options_order' => HeaderVariables::$order_array
                    ]);
                } else {
                    return redirect()->route('shop.index');                
                }
            } else {
                return redirect()->route('login.index'); 
            }
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred showing product create view: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }

    //Función que trata petición POST para guardar un nuevo producto
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_name' => 'required|string|max:255',
                'product_description' => 'required|string',
                'product_price' => 'required|numeric|min:0',
                'product_image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'product_tag' => 'nullable|string',
                'product_category' => 'required|exists:categories,id',
            ], ValidationMessages::productValidationMessages());
            $productImages = $request->file('product_image');
            if ($productImages) {   
                if (count($productImages) > 4) {
                    return redirect()->back()->withErrors(['product_image' => 'You can upload a maximum of 4 images.']);
                }
            } else {
                return redirect()->back()->withErrors(['product_image' => 'You can not upload 0 images.']);
            }
            $id = Auth::user()->id;
            $shopID = Shop::findShopUserID($id);
            $product = Product::create([
                'name' => $validatedData['product_name'],
                'description' => $validatedData['product_description'],
                'price' => $validatedData['product_price'],
                'tag' => $validatedData['product_tag'],
                'shop_id' => $shopID,
            ]);

            /**
             * FUNCIONALIDAD SUBIR UNA IMAGEN A LA API AL CREAR EL PRODUCTO
             */
            $client = new Client();

            $imagePaths = [];
            $main = true;
            try {
                foreach ($productImages as $image) {
                    $name = uniqid('product_') . '.' . $image->getClientOriginalExtension();
                    
                    $path = env('API_IP')."images/products/";
                    $finalPath = $path . $name;
                    
                    $client->post(env('API_IP') . 'api/images/insert', [
                        'verify' => false,
                        'multipart' => [
                            [
                                'name' => 'name',
                                'contents' => $name,
                            ],
                            [
                                'name' => 'path',
                                'contents' => $finalPath,
                            ],
                            [
                                'name' => 'product_image',
                                'contents' => fopen($image, 'r'),
                                'filename' => $name,
                            ],
                            [
                                'name' => 'product_id',
                                'contents' => $product->id,
                            ],
                            [
                                'name' => 'main',
                                'contents' => $main,
                            ],
                        ],
                    ]);
                    $main = false;
                }
            } catch (\Exception $e) {
                LOG::ERROR($e->getMessage());
            }
            Log::channel('marketify')->info("Product #$product->id created succesfully");
            $category_product = Category_product::create([
                'product_id' => $product->id,
                'category_id' => $validatedData['product_category'],
            ]);
            
            return redirect()->route('shop.admin');
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred creating a new product: ' . $e->getMessage());
        }
    }

    /**
     * Función que trata petición POST para actualizar un producto
     */
    public function update(Request $request, $id) {
        try {
            $validatedData = $request->validate([
                'product_name' => 'required|string|max:255',
                'product_description' => 'required|string',
                'product_price' => 'required|numeric|min:0',
                'product_image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'product_tag' => 'nullable|string',
                'product_category' => 'required|exists:categories,id',
            ], ValidationMessages::productValidationMessages());
            $productImages = $request->file('product_image');
            if ($productImages) {   
                if (count($productImages) > 4) {
                    return redirect()->back()->withErrors(['product_image' => 'You can upload a maximum of 4 images.']);
                }
            } else {
                return redirect()->back()->withErrors(['product_image' => 'You can not upload 0 images.']);
            }
            $product = Product::findOrFail($id);
            $product->update([
                'name' => $validatedData['product_name'],
                'description' => $validatedData['product_description'],
                'price' => $validatedData['product_price'],
                'tag' => $validatedData['product_tag']
            ]);

            $Category_Product = Category_Product::findCat_ProByProduct($product->id);
            
            $Category_Product->update([
                'category_id' => $validatedData['product_category'],
            ]);
            
            
            
            $client = new Client();
            $response = $client->delete(env('API_IP').'api/images/delete/product/'.$id, [
                'verify' => false,
            ]);
            
            $main = true;
            try {
                foreach ($productImages as $image) {
                    $name = uniqid('product_') . '.' . $image->getClientOriginalExtension();
                    
                    $path = env('API_IP')."images/products/";
                    $finalPath = $path . $name;
                    
                    $client->post(env('API_IP') . 'api/images/insert', [
                        'verify' => false,
                        'multipart' => [
                            [
                                'name' => 'name',
                                'contents' => $name,
                            ],
                            [
                                'name' => 'path',
                                'contents' => $finalPath,
                            ],
                            [
                                'name' => 'product_image',
                                'contents' => fopen($image, 'r'),
                                'filename' => $name,
                            ],
                            [
                                'name' => 'product_id',
                                'contents' => $product->id,
                            ],
                            [
                                'name' => 'main',
                                'contents' => $main,
                            ],
                        ],
                    ]);
                    $main = false;
                }
            } catch (\Exception $e) {
                LOG::ERROR($e->getMessage());
            }

            Log::channel('marketify')->info("Product updated succesfully");
            session()->flash('status', "Product '$product->name' edited successfully.");
            return redirect()->route('shop.admin');
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred updating an existing product: ' . $e->getMessage());
        }
    }


    /**
     * Función que trata petición POST para borrar un producto
     */
    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            $product->delete();

            Log::channel('marketify')->info("Product #$product->id deleted succesfully");
            session()->flash('status', "Product '$product->name' deleted successfully.");
            return redirect()->route('shop.admin');
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred deleting an existing product: ' . $e->getMessage());
        }
    }

    /**
     * Función que trata petición POST para esconder/mostrar un producto
     */
    public function hide(Request $request, $id)
    {
        try {
            $product = Product::find($id);
            if ($product->status == 'hidden') {
                $product->status = 'active';
                session()->flash('status', "Product '$product->name' has been set to visible successfully.");
                Log::channel('marketify')->info("Product set to visible successfully");
            } else {
                $product->status = 'hidden';
                session()->flash('status', "Product '$product->name' has been hidden successfully.");
                Log::channel('marketify')->info("Product hidden successfully");
            }
            $product->save();
            return redirect()->route('shop.admin');
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred hidding an existing product: ' . $e->getMessage());
        }
    }

    /**
     * Vista para editar un producto
     */
    public function edit($id)
    {
        try {
            $categories = Category::all();
            $product = Product::find($id);
            if ($product->shop->user_id == auth()->id()) { 
                Log::channel('marketify')->info('product.edit view loaded');
                return view('product.edit', [
                    'categories' => $categories,
                    'options_order' => HeaderVariables::$order_array,
                    'product' => $product
                ]);
            } else {
                Log::channel('marketify')->info('Redirect to product.show');
                return redirect()->route('product.show', $id);
            }
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred showing product edit view: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }

    /**
     * Vista para filtrar según la categoria seleccionada en la landing page
     */
    public function filterCategory($id)
    {
        try {
            $userId = auth()->id();
            if ($userId) {
                $arrayCart = Cart::showCartByUserID($userId);
            } else {
                $arrayCart = "[]";
            }
            setcookie("arrayCart", $arrayCart);

            $usersShop = Shop::findShopUserID($userId);
            if ($usersShop) {
                $shop = Shop::findOrFail($usersShop);
            } else {
                $shop = 0;
            }
            $categories = Category::all();

            $products = Product::filterCategory($id);


            // Hacemos la petición a la api
            $client = new Client();

            $response = $client->get(env('API_IP').'api/images/view/all', [
                'verify' => false
            ]);
            $data = json_decode($response->getBody(), true);

            $paths = [];
            foreach ($data as $ruta ) {
                array_push($paths,$ruta);          
            }


            Log::channel('marketify')->info('product.index with filter view loaded');
            return view('product.index', [
                'products' => $products,
                'categories' => $categories,
                'options_order' => HeaderVariables::$order_array,
                'shop' => $shop,
                'paths' => $paths
            ]);
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred showing product index with filter view: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }
}
