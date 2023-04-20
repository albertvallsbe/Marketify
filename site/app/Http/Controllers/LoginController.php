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

class LoginController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('login.index', [
            'categories' => $categories,
            'options_order' => Order::$order_array
        ]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'current-password' => 'required|string',
        ]);

        $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        
        $credentials = [
            $loginType => $request->input('login'),
            'password' => $request->input('current-password')
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
        $email = $request->input('remember-password');
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
        $email = $request->input('email');
        $password = $request->input('remember-password');
        $repeatpassword = $request->input('repeat-password');

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
