<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\ConfirmMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Console\View\Components\Confirm;

class RegisterController extends Controller
{
    public function index(){
        return view('register.index');
    }
    public function register(Request $request)
    {
        // $request->validate([
        //     'register-email' => 'required|string|email|max:255|unique:users',
        //     'register-username' => 'required|string|max:255|unique:users',
        //     'current-password' => 'required|string|min:8|confirmed',
        // ]);
        // $data = $request->all();

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required|string|regex:/^[a-zA-Z]+$/u|max:255|min:3|unique:users',
            'password' => 'required|string|min:8|max:255',
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than :max characters.',
            'email.unique' => 'The email has already been taken.',

            'name.required' => 'The username field is required.',
            'name.regex' => 'The username field format is invalid.',
            'name.max' => 'The username may not be greater than :max characters.',
            'name.min' => 'The username must be at least :min characters.',
            'name.unique' => 'The username has already been taken.',

            'password.required' => 'The password field is required.',
            'password.max' => 'The password may not be greater than :max characters.',
            'password.min' => 'The password must be at least :min characters.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
            User::create([
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'password' => Hash::make($request->input('password')), 
                'api_token' => Str::random(60),
            ]);

            $email = $request->input('email');
            $confirmMail = new ConfirmMail;
            Mail::to($email)->send($confirmMail);
            session()->flash('status', 'Email send.');

            return redirect(route('login.index'));
    }
   
}
