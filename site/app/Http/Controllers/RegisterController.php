<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\ConfirmMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\View\Components\Confirm;

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
            'register-username'=>'required',
            
           
        ]);
        $data = $request->all();
        
        
       
        User::create([
            'email' => $data['register-email'],
            'name'=>$data['register-username'],
            'password' => Hash::make($data['register-password']),
            'api_token'=> Str::random(60),
            
        ]);

        $email = $request->input('register-email');
        $correo = new ConfirmMail;
        Mail::to($email)->send($correo);
        session()->flash('status', 'Email send.');

        return redirect(route('login.index'));
    }
   
}
