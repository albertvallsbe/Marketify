<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\RememberPassword;
use App\Classes\HeaderVariables;
use App\Helpers\ValidationMessages;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{

    /**
     * Control del login de usuario
     */
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

    /**
     * Login de usuario
     */
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

    /**
     * Control del password
     */
    public function password()
    {
        return view('login.formEmail');
    }

    /**
     * Remember del password de usuario
     */
    public function sendEmail(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
            ]);

            $user = User::where('email', $validatedData['email'])->first();

            if (!$user) {
                Log::channel('marketify')->info('The provided email address does not exist.');
                return redirect()->back()->with('error', 'The provided email address does not exist.');
            }

            $token = Str::random(32); // Generate a random token
            $user->remember_token = $token;
            $user->save();

            $correo = new RememberPassword($user, $token);
            Mail::to($validatedData['email'])->send($correo);

            Log::channel('marketify')->info('The email for password reset has been sent successfully.');
            return redirect()->back()->with('status', 'An email with password reset instructions has been sent to your email address.');
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred while sending the password reset email.', ["e" => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while sending the password reset email.');
        }
    }

    public function showResetForm(Request $request)
    {
        try {
            $token = $request->query('token');
            return view('login.formNewPassword', ['token' => $token]);
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred while loading the Reset Password view.', ['e' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while loading the Reset Password view.');
        }
    }

        /**
     * Restablecer la contraseña del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'token' => 'required',
                'new-password' => 'required|string|min:8|max:255|same:repeat-password',
                'repeat-password' => 'required|string|min:8|max:255|same:new-password',
            ]);

            $user = User::where('remember_token', $validatedData['token'])
                ->first();

            if (!$user) {
                Log::info('The provided email or token is invalid.');
                return redirect()->back()->with('error', 'The provided email or token is invalid.');
            }

            $user->password = Hash::make($validatedData['new-password']);
            $user->remember_token = null;
            $user->save();

            Log::info('The password has been reset successfully.');
            return redirect(route('login.index'))->with('status', 'Your password has been reset successfully. You can now log in with your new password.');
        } catch (\Exception $e) {
            Log::error('An error occurred while resetting the password.', ['e' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while resetting the password.');
        }
    }
}
