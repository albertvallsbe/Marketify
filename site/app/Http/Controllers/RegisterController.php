<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index(){
        return view('register.index');
    }
    public function register(Request $request)
    {
        $request->validate([
            'register-email' => 'required',
            'register-password' => 'required',
        ]);
        $data = $request->all();

        User::create([
            'email' => $data['register-email'],
            'password' => Hash::make($data['register-password']),
        ]);

        
        Log::channel('desarrollo')->info('Usuario registrado');

        return redirect(route('login.index'));
    }
}
