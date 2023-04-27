<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing.index');

Route::get('/search', [ProductController::class,'index'])->name('product.index');
Route::get('/search/{id}', [ProductController::class, 'filterCategory'])->name('product.filter');
// Route::get('/products', [RegisterController::class, 'index'])->name('products.index');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
// Route::post('/product', [ProductController::class, 'store'])->name('products');
Route::post('/product', [ProductController::class, 'store'])->name('product.store');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
// Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::put('/product/edit/{id}', [ProductController::class, 'update'])->name('product.update');
Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
Route::post('/product/hide/{id}', [ProductController::class, 'hide'])->name('product.hide');

Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/register', [RegisterController::class, 'register'])->name('auth.register');

Route::get('/login/password', [LoginController::class, 'password'])->name('login.password');
Route::post('/login/password', [LoginController::class, 'remember'])->name('login.remember');
Route::get('/login/password/email', [LoginController::class, 'email'])->name('login.email');
Route::post('/login/password/email', [EmailController::class, 'return'])->name('email.return');
Route::get('/login/password/remember', [LoginController::class, 'rememberView'])->name('login.rememberView');
Route::post('/login/password/remember', [LoginController::class, 'rememberpassw'])->name('login.rememberpassw');
Route::get('/login', [LoginController::class, 'index'])->name('login.index');
Route::post('/login', [LoginController::class, 'login'])->name('auth.login');

Route::get('/user/edit', [UserController::class, 'edit'])->name('user.edit');
Route::post('/user/edit', [UserController::class, 'changeData'])->name('user.changeData');
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
Route::get('/logout', [UserController::class, 'logout'])->name('user.logout');


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
Route::post('/getcart', [CartController::class, 'getCart'])->name('cart.get');

Route::put('/shop/admin', [ShopController::class, 'update'])->name('shop.update');
Route::get('/shop/admin', [ShopController::class, 'admin'])->name('shop.admin');
Route::get('/shop/admin/edit', [ShopController::class, 'edit'])->name('shop.edit');
Route::get('/shop/{url}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::post('/shop/create', [ShopController::class, 'create'])->name('shop.create');

Route::get('/auth', function () {
    $authenticated = Auth::check();
    return response()->json(['authenticated' => $authenticated]);
});


Route::get('/product-not-found', [ErrorController::class, 'product404'])->name('product.404');
Route::get('/shop-not-found', [ErrorController::class, 'shop404'])->name('shop.404');
Route::get('/user-not-found', [ErrorController::class, 'user404'])->name('user.404');
Route::fallback([ErrorController::class, 'generic404'])->name('generic.404');

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function() {
    //     return view('dashboard');
    // })->name('dashboard');
    
    
    // Route::resource('dashboard', App\Http\Controllers\DashboardController::class)
    //     ->middleware('auth');
