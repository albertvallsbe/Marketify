<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/images', [ImageController::class, 'index']);
Route::get('/images/{id}', [ImageController::class, 'catchImage']);

Route::post('/insert', [ImageController::class, 'insertImage']);

Route::delete('/delete/{id}', [ImageController::class, 'deleteImage']);
Route::delete('/delete/product/{id}', [ImageController::class, 'deleteAll']);




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
