<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop;
use App\Classes\HeaderVariables;
use App\Models\Product;
use App\Models\Category;
use App\Models\Notification;

use Illuminate\Http\Request;
use App\View\Components\Header;
use App\Models\Category_Product;
use App\Helpers\ValidationMessages;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller {
    /**
     * Vista principal de los productos
     */
    public function index(Request $request) {
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
            setcookie("arrayCart",$arrayCart);

            //Comprobamos la ID del usuario y si le pertenece una tienda, para comprobar si le pertenece el producto mostrado.
            $usersShop = Shop::findShopUserID($userId);
            if($usersShop){
                $shop = Shop::findOrFail($usersShop);
            }else{
                $shop = 0;
            }
            Log::channel('marketify')->info('product.index view loaded');
            return view('product.index', [
                'products' => $products,
                'categories' => $categories,
                'options_order' => HeaderVariables::$order_array,
                'shop' => $shop,
                'notificationCount' => $notificationCount]);
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred showing product view: '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }

    /**
     * Vista detalle del producto
     */
    public function show($id) {
        try {
            $product = Product::findOrFail($id);
            $productShop = $product->shop;
            $categories = Category::all();

                //Comprobamos la ID del usuario y si le pertenece una tienda, para comprobar si le pertenece el producto mostrado.
                $userId = auth()->id();
                $usersShop = Shop::findShopUserID($userId);
                if(!$usersShop){
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
                            'shopname' => $shopName,
                            'shop' => $shop,
                            'categoryname' => $categoryName
                        ]);
                    } else {
                        return redirect()->route('product.index');
                    }
                }
                } catch (ModelNotFoundException $e) {
                    Log::channel('marketify')->error('An error occurred showing product show view: '.$e->getMessage());
            return redirect()->route('product.404');
        }
    }

    /**
     * Vista para creación de producto
     */
    public function create() {
        try {
            $categories = Category::all();
            Log::channel('marketify')->info('product.create view loaded');
            return view('product.create', [
                'categories' => $categories,
                'options_order' => HeaderVariables::$order_array
            ]);
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred showing product create view: '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }

    /**
     * Función que trata petición POST para guardar un nuevo producto
     */
    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'product_name' => 'required|string|max:255',
                'product_description' => 'required|string',
                'product_price' => 'required|numeric|min:0',
                'product_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'product_tag' => 'nullable|string',
                'product_category' => 'required|exists:categories,id',
            ], ValidationMessages::productValidationMessages());

            if ($request->hasFile('product_image')) {
                $image = $validatedData['product_image'];

                $name = uniqid('product_') . '.' . $image->extension();
                $path = 'images/products/';
                $image->move($path, $name);
                $imagePath = $path . $name;
            }

                $id = Auth::user()->id;
                $shopID = Shop::findShopUserID($id);
                $product = Product::create([
                'name' => $validatedData['product_name'],
                'description' => $validatedData['product_description'],
                'price' => $validatedData['product_price'],
                'tag' => $validatedData['product_tag'],
                'shop_id' => $shopID,
                'image' => $imagePath ?? 'images/products/default-product.png',
            ]);

            Log::channel('marketify')->info("Product created succesfully");
            $category_product = Category_product::create([
            'product_id' => $product->id,
            'category_id' => $validatedData['product_category'],
            ]);
            return redirect()->route('shop.admin');
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred creating a new product: '.$e->getMessage());
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
            'product_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_tag' => 'nullable|string',
            'product_category' => 'required|exists:categories,id',
        ], ValidationMessages::productValidationMessages());

        if ($request->hasFile('product_image')) {
            $image = $validatedData['product_image'];

            $name = uniqid('product_') . '.' . $image->extension();
            $path = 'images/products/';
            $image->move($path, $name);
            $imagePath = $path . $name;
        }
        $product=Product::findOrFail($id);

            $product->update([
                'name' => $validatedData['product_name'],
                'description' => $validatedData['product_description'],
                'price' => $validatedData['product_price'],
                'tag' => $validatedData['product_tag'],
                'image' => $imagePath ?? $product->image,
            ]);
            $Category_Product = Category_Product::findCat_ProByProduct($product->id);
            $Category_Product->update([
                'category_id' => $validatedData['product_category'],
            ]);

            Log::channel('marketify')->info("Product updated succesfully");
            session()->flash('status', "Product '$product->name' edited successfully.");
            return redirect()->route('shop.admin');
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred updating an existing product: '.$e->getMessage());
        }
    }

    /**
     * Función que trata petición POST para borrar un producto
     */
    public function destroy($id) {
        try {
            $product = Product::find($id);
            $product->delete();

            Log::channel('marketify')->info("Product deleted succesfully");
            session()->flash('status', "Product '$product->name' deleted successfully.");
            return redirect()->route('shop.admin');
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred deleting an existing product: '.$e->getMessage());
        }
    }

    /**
     * Función que trata petición POST para esconder/mostrar un producto
     */
    public function hide(Request $request, $id) {
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
            Log::channel('marketify')->error('An error occurred hidding an existing product: '.$e->getMessage());
        }
    }

    /**
     * Vista para editar un producto
     */
    public function edit($id) {
        try {
            $categories = Category::all();
            $product = Product::find($id);

            Log::channel('marketify')->info('product.edit view loaded');
            return view('product.edit', [
                'categories' => $categories,
                'options_order' => HeaderVariables::$order_array,
                'product' => $product
            ]);
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred showing product edit view: '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }

    /**
      * Vista para filtrar según la categoria seleccionada en la landing page
     */
    public function filterCategory($id) {
        try {
            $userId = auth()->id();
            if ($userId) {
                $arrayCart = Cart::showCartByUserID($userId);
            } else {
                $arrayCart = "[]";
            }
            setcookie("arrayCart",$arrayCart);

            $usersShop = Shop::findShopUserID($userId);
            if($usersShop){
                $shop = Shop::findOrFail($usersShop);
            }else{
                $shop = 0;
            }
            $categories = Category::all();

            $products = Product::filterCategory($id);
            Log::channel('marketify')->info('product.index with filter view loaded');
            return view('product.index', [
                'products' => $products,
                'categories' => $categories,
                'options_order' => HeaderVariables::$order_array,
                'shop' => $shop
            ]);
        } catch (\Exception $e) {
        Log::channel('marketify')->error('An error occurred showing product index with filter view: '.$e->getMessage());
        return redirect()->back()->with('error', 'An error occurred.');
        }
    }
}
