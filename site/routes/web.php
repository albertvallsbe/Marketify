<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LoginController;

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

Route::get('/login', [LoginController::class, 'index'])->name('login.index');


Route::get('/card', [CartController::class, 'index'])->name('card.index');
Route::post('/card', [CartController::class, 'add'])->name('card.add');

