<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function return(){
        return view('emails.confirm');
    }
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
