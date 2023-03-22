<?php

namespace App\Http\Controllers;

use App\Classes\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index() {
        $categories = Category::all();

        return view('login.index',['categories' => $categories,
        'options_order' => Order::$order_array]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            Log::channel('desarrollo')->info('Usuario logueado');
            return redirect()->intended(route('product.index'));
        } else {
            session()->flash('status', 'Incorrect username or password!');

            return redirect(route('login.index'));
        }
    }
}
