@extends('layouts.users')

@section('title', 'Forgotten password?')

@section('content')
    <section class="main-newpassword">
        <section class="card-style-newpassword remember-section remember-container">
            <h1 class="card-style-newpassword_title remember-title">Have you forgotten the password?</h1>
            <p class="card-style-newpassword_content remember-content">Have you forgotten the password? Don't worry. Enter
                your new password and we will update your new password.
                <br><br>If you want to return, press
                <a href={{ route('login.index') }}>Login</a>
            </p>
            <form class="form" method="POST" action="{{ route('login.resetPassword') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <label class="form_label" for="remember-password">Your new password:</label>
                <input type="password" class="form_input rememberpassw" placeholder="Enter your new password"
                    name="new-password">
                @error('new-password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                
                <label class="form_label" for="remember-password">Repeat your new password:</label>
                <input type="password" class="form_input rememberpassw" placeholder="Repeat your new password"
                    name="repeat-password">
                @error('repeat-password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                
                <button class="general-button btn-password">UPDATE PASSWORD</button>

                @if (session()->has('status'))
                    <p class="card-style-password_content">{{ session()->get('status') }}</p>
                @endif

                @if (session()->has('error'))
                    <p class="card-style-password_content">{{ session()->get('error') }}</p>
                @endif
            </form>
        </section>
        <section class="login-text intro-style-newpassword">
            <a href="{{ route('product.index') }}">
                <img class="intro-style-newpassword_logo" src="{{ asset('images/marketify_logo.png') }}">
            </a>
            <h1 class="intro-style-newpassword_title">LOG IN TO ENJOY OUR APP!</h1>
            <p class="intro-style-newpassword_text">This is an application that allows you to buy and sell products at the same
                time. In this way, you can be a buyer and a seller simultaneously.<br><br>By: Albert Valls, David Hernández
                & Oscar Ramírez.</p>
        </section>
    </section>
@endsection
