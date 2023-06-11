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
Route::prefix('images')->withoutMiddleware('throttle:api')->middleware('throttle:300:1')->group(function () {
    Route::get('/view/all', [ImageController::class, 'index'])->name('api.images.index');
    Route::get('/view/{id}', [ImageController::class, 'show'])->name('api.images.show');
    Route::post('/insert/seeder', [ImageController::class, 'insertSeeder'])->name('api.images.insertseeder');
    Route::post('/insert', [ImageController::class, 'insert'])->name('api.images.insert');
    Route::delete('/delete/{id}', [ImageController::class, 'delete'])->name('api.images.delete');
    Route::delete('/delete/product/{id}', [ImageController::class, 'deleteAll'])->name('api.images.deleteAll');
});
