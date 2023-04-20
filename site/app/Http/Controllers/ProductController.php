<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop;
use App\Classes\Order;
use App\Models\Product;

use App\Models\Category;
use Illuminate\Http\Request;
use App\View\Components\Header;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $header = new Header($request);
        
        $categories = Category::all();
        if($request->session()->has('request_categories') == ""){
            $products = Product::searchAll(session('request_search'), session('request_order'));
        }else {
            $products = Product::searchSpecific(session('request_search'), session('request_categories'), session('request_order'));
        }
        $products->appends([
            'filter' => session('request_categories'),
            'search' => session('request_search'),
            'order' => session('request_order')
        ]);

        $userId = auth()->id();
        if ($userId) {
            $arrayCart = Cart::showCartByUserID($userId);
        }else{
            $arrayCart = "[]";
        }
        setcookie("arrayCart",$arrayCart);
        return view('product.index', [
            'products' => $products,
            'categories' => $categories,
            'options_order' => Order::$order_array]);
    }

    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            $categories = Category::all();
            return view('product.show', ['product' => $product,
            'categories' => $categories,
            'options_order' => Order::$order_array]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('product.404');
        }
    }



    public function create() {        
        $categories = Category::all();
        return view('product.create', ['categories' => $categories,
        'options_order' => Order::$order_array]);
    }

    public function store(Request $request)
    {
        // $id=auth()->user->id; Quan treballem amb usuaris, per recuperar id de l'usuari logat

        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string',
            'product_price' => 'required|numeric|min:0',
            'product_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_tag' => 'nullable|string',
            'product_category' => 'required|exists:categories,id',
        ], [
            'product_name.required' => 'The name field is required.',
            'product_name.string' => 'The name field must be a string.',
            'product_name.max' => 'The name field may not be greater than 255 characters.',
            'product_description.required' => 'The description field is required.',
            'product_description.string' => 'The description field must be a string.',
            'product_price.required' => 'The price field is required.',
            'product_price.numeric' => 'The price field must be a number.',
            'product_price.min' => 'The price field must be at least 0.',
            'product_image.image' => 'The image field must be an image.',
            'product_image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'product_image.max' => 'The image may not be greater than 2048 kilobytes.',
            'product_tag.string' => 'The tag field must be a string.',
            'product_category.required' => 'The category field is required.',
            'product_category.exists' => 'The selected category is invalid.',
        ]);

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            
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
            // 'category_id' => $validatedData['product_category'],
            'tag' => $validatedData['product_tag'],
            'shop_id' => $shopID,
            'image' => $imagePath ?? null,
        ]);
    

    
        return redirect()->route('products.index');
    }

    public function update(Request $request)
    {
        $product=Product::findOrFail($request->id);
        $product->update($request->all());

        return redirect()->route('product.edit', $product);
    }
}
