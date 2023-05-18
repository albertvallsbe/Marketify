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
use App\Helpers\ValidationMessages;

class RegisterController extends Controller
{
    public function index(){  
        try {
            if(auth()->user()) {
                return redirect()->route('user.edit');
            }else{
                return view('register.index');
            }
        } catch(\Exception $e) {

            Log::channel('marketify')->info('The register page do not charge.', ["e" => $e->getMessage()]);
            return redirect(route('login.index'));
        
        }
        
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
            'name' => 'required|string|alpha_num|max:255|min:3|unique:users',
            'password' => 'required|string|min:8|max:255',
        ], ValidationMessages::userValidationMessages());
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
            User::create([
                'email' => $validator->validated()['email'],
                'name' => $validator->validated()['name'],
                'password' => Hash::make($validator->validated()['password']), 
                'api_token' => Str::random(60),
            ]);

            $email = $validator->validated()['email'];
            $confirmMail = new ConfirmMail;
            Mail::to($email)->send($confirmMail);
            session()->flash('status', 'Email send.');

            return redirect(route('login.index'));
    }
   
}
