<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop;
use App\Classes\Order;
use App\Models\Product;

use Illuminate\Support\Facades\Log;
use App\Models\Category;
use Illuminate\Http\Request;
use App\View\Components\Header;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\ValidationMessages;

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
        ], ValidationMessages::productValidationMessages());

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
            'category_id' => $validatedData['product_category'],
            'tag' => $validatedData['product_tag'],
            'shop_id' => $shopID,
            'image' => $imagePath ?? 'images/products/default-product.png',
        ]);
    

    
        return redirect()->route('shop.edit');
    }

    public function update(Request $request, $id)
    {$validatedData = $request->validate([
        'product_name' => 'required|string|max:255',
        'product_description' => 'required|string',
        'product_price' => 'required|numeric|min:0',
        'product_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'product_tag' => 'nullable|string',
        'product_category' => 'required|exists:categories,id',
    ], ValidationMessages::productValidationMessages());
    
    if ($request->hasFile('product_image')) {
        $image = $request->file('product_image');
        
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
            'product_category' => $validatedData['product_category'],
            'image' => $imagePath ?? $product->image,
        ]);
        session()->flash('status', "Product '$product->name' edited successfully.");
        return redirect()->route('shop.edit');
    }

    public function destroy($id) {
    $product = Product::find($id);
    $product->delete();
    
    session()->flash('status', "Product '$product->name' deleted successfully.");
    return redirect()->route('shop.edit');
}

public function hide(Request $request, $id) {
$product = Product::find($id);
if ($product->hidden == true) {
    $product->hidden = false;
    session()->flash('status', "Product '$product->name' has been set to visible successfully.");
}else{
    $product->hidden = true;
    session()->flash('status', "Product '$product->name' has been hidden successfully.");
}
$product->save();

return redirect()->route('shop.edit');
}

public function edit($id)
{
    $categories = Category::all();
    $product = Product::find($id);
    return view('product.edit', ['categories' => $categories,
    'options_order' => Order::$order_array,
    'product' => $product]);
}
}
