<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Retorno de la confirmación de email.
     */
    public function return(){
        try{

            Log::channel('marketify')->info('Return confirmation email');

            return view('emails.confirm');

        }catch (\Exception $e) {

            Log::channel('marketify')->info('The email has not been sent.', ["e" => $e->getMessage()]);
            return redirect(route('login.index'));
        }
    }

    /**
     * Envio de email
     */
    public function sendEmail($value)
    {
        try{

            $correo = new ConfirmMail;
            Mail::to($value)->send($correo);
            session()->flash('status', 'Email send.');
            Log::channel('marketify')->info('The email has been sent.');

        }catch (\Exception $e) {

            Log::channel('marketify')->info('The email has not been sent.', ["e" => $e->getMessage()]);
            return redirect(route('login.index'));

        }
    }
}
