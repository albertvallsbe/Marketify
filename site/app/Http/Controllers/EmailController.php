<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function return(){
        return view('emails.confirm');
    }
    public function sendEmail()
    {
        $correo = new ConfirmMail;
        Mail::to('oscarferram@gmail.com')->send($correo);
        session()->flash('status', 'Email send.');
    }
}
