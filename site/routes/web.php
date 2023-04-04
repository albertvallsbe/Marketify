<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ProductController::class,'index'])->name('product.index');
Route::get('/product/show/{id}', [ProductController::class, 'show'])->name('product.show');
Route::post('products', [ProductController::class, 'store'])->name('products');

Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/register', [RegisterController::class, 'register'])->name('auth.register');

Route::get('/login/password', [LoginController::class, 'password'])->name('login.password');
Route::post('/login/password', [LoginController::class, 'remember'])->name('login.remember');
Route::get('/login/password/email', [LoginController::class, 'email'])->name('login.email');
Route::get('/login/password/remember', [LoginController::class, 'rememberView'])->name('login.rememberView');
Route::post('/login/password/remember', [LoginController::class, 'rememberpassw'])->name('login.rememberpassw');
Route::post('/login/password/email', [EmailController::class, 'return'])->name('email.return');
Route::get('/login', [LoginController::class, 'index'])->name('login.index');
Route::post('/login', [LoginController::class, 'login'])->name('auth.login');

Route::get('/user/edit', [UserController::class, 'edit'])->name('user.edit');
Route::post('/user/edit', [UserController::class, 'changeData'])->name('user.changeData');
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
Route::get('/logout', [UserController::class, 'logout'])->name('user.logout');


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// Route::post('/cart', [CartController::class, 'add'])->name('cart.add');

Route::get('/shop/edit', [ShopController::class, 'edit'])->name('shop.edit');
Route::get('/shop/{id}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::post('/shop/create', [ShopController::class, 'create'])->name('shop.create');