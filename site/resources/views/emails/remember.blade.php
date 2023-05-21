@extends('layouts.users')

@section('title', 'Remember password')

@section('content')
    <section>
        <h1 class="password-title">Have you forgotten the password?</h1>
        <p>Hello,</p>
        <p>You have requested to reset your password. Please click the link below to proceed:</p>
        <p><a href="{{ route('login.receivedEmail', ['token' => $token]) }}">Reset Password</a></p>
        <p>If you did not request a password reset, no further action is required.</p>
    </section>
@endsection
