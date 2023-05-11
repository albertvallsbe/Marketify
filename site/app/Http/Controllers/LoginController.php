<?php

namespace App\Http\Controllers;

use App\Classes\HeaderVariables;
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


    public function index()
    {
        try {
            if (auth()->user()) {
                Log::channel('marketify')->info('The user edit view has been loaded successfully.');
                return redirect()->route('user.edit');
            } else {
                Log::channel('marketify')->info('The login view has been loaded successfully.');
                return view('login.index');
            }
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred while loading Login view', ["e" => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while loading Login view) ');
        }
    }

    public function login(Request $request)
    {


        try {
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
                Log::channel('marketify')->info('You have successfully logged in!');
                return redirect()->intended(route('product.index'));
            } else {
                Log::channel('marketify')->info('Incorrect username or password!');
                session()->flash('status', 'Incorrect username or password!');
                return redirect(route('login.index'));
            }
        } catch (\Exception $e) {

            Log::channel('marketify')->info('Incorrect username or password!', ["e" => $e->getMessage()]);
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
        try {
            $validatedData = $request->validate([
                'remember-password' => 'required|string|min:8|max:255',
            ], ValidationMessages::userValidationMessages());

            $email = $validatedData['remember-password'];
            $correo = new RememberPassword;

            Mail::to($email)->send($correo);
            session()->flash('status', 'Email send.');
            Log::channel('marketify')->debug('Mail send successfuly!');
            return redirect(route('login.password'));
        } catch (\Exception $e) {
            Log::channel('marketify')->debug('The email has not been sent!', ["e" => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while sending the email) ');
        }
    }

    public function rememberView()
    {
        return view('login.newPassword');
    }

    public function rememberpassw(Request $request)
    {
       
        // $validatedData = $request->validate([
        //     'email' => 'required|string|email1|max:255|unique:users',
        //     'remember-password' => 'required|string|min:8|max:255|same:remember-password',
        //     'repeat-password' => 'required|string|min:8|max:255|same:repeat-password',
        // ], ValidationMessages::userValidationMessages());

        
        $email = $request['email1'];
        $password = $request['remember-password'];
        $repeatpassword = $request['repeat-password'];

        $value = $email;
        $id_user = User::catchId($value);
        // dd($id_user);
        
        if ($password == $repeatpassword) {
            
            $users = User::updatePassword($id_user, $password);
            $users->save();
           
            return redirect(route('login.index'));
        } else {
            session()->flash('status', 'Passwords do not match.');
        }
    }
}
