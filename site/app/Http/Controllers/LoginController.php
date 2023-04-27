<?php

namespace App\Http\Controllers;

use App\Classes\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Mail\RememberPassword;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Helpers\ValidationMessages;

class LoginController extends Controller
{
    public function index(){    
        if(auth()->user()) {
            return redirect()->route('user.edit');
        }else{
            return view('login.index');
        }
    }
    
    public function login(Request $request)
    { 
        $validatedData = $request->validate([
            'login' => 'required|string|max:255',
            'current-password' => 'required|string|min:8|max:255',
        ], ValidationMessages::userValidationMessages());

        $loginType = filter_var($validatedData['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        
        $credentials = [
            $loginType => $validatedData['login'],
            'password' => $validatedData['current-password'],
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('product.index'));
        } else {
            session()->flash('status', 'Incorrect username or password!');
            return redirect(route('login.index'));
        }
    }

    public function password()
    {
        return view('login.password');
    }

    public function remember(Request $request)
    {
        $validatedData = $request->validate([
            'remember-password' => 'required|string|min:8|max:255',
        ], ValidationMessages::userValidationMessages());

        $email = $validatedData['remember-password'];
        $correo = new RememberPassword;

        Mail::to($email)->send($correo);
        session()->flash('status', 'Email send.');
        return redirect(route('login.password'));
    }

    public function rememberView(){
        return view('login.newPassword');
    }
    
    public function rememberpassw(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'remember-password' => 'required|string|min:8|max:255|same:repeat-password',
            'repeat-password' => 'required|string|min:8|max:255|same:remember-password',
        ], ValidationMessages::userValidationMessages());

        $email = $validatedData['email'];
        $password = $validatedData['remember-password'];
        $repeatpassword = $validatedData['repeat-password'];

        $value = $email;
        $id_user = User::catchId($value);

        if($password == $repeatpassword){
            $users = User::updatePassword($id_user,$password);
            $users->save();
            return redirect(route('login.index'));
        }else {
            session()->flash('status', 'Passwords do not match.');
        }
    }
}
