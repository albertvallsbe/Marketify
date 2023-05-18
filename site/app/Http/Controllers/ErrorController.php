<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Classes\HeaderVariables;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ErrorController extends Controller
{
    /**
     * Error tienda no encontrada
     */
    public function shop404() {
        $categories = Category::all();
        Log::channel('marketify')->info('Error shop not found 404.');

        return view('errors.404-shop',['categories' => $categories,
        'options_order' => HeaderVariables::$order_array]);
    }

    /**
     * Error producto no encontrado
     */
    public function product404() {
        $categories = Category::all();

        Log::channel('marketify')->info('Error product not found 404.');
        return view('errors.404-product',['categories' => $categories,
        'options_order' => HeaderVariables::$order_array]);
    }

    /**
     * Error usuario no encontrado
     */
    public function user404() {
        $categories = Category::all();

        Log::channel('marketify')->info('Error user not found 404.');

        return view('errors.404-user',['categories' => $categories,
        'options_order' => HeaderVariables::$order_array]);
    }

    /**
     * Error general, not found
     */
    public function generic404() {
        $categories = Category::all();

        Log::channel('marketify')->info('Error not found 404.');
        return view('errors.404-generic',['categories' => $categories,
        'options_order' => HeaderVariables::$order_array]);
    }
}
