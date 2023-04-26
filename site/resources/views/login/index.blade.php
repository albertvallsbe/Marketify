@extends('layouts.users')

@section('title', 'Log In')

@section('content')
    <section class="main-login">
        <section class="card-style-login">
            <h1 class="card-style-login_title login-title">Login</h1>
            <form class="form" method='POST'action="{{ route('auth.login') }}">
                @csrf
                <label class="form_label" for="login">Your email/username</label>
                <input type="text" class="form_input input-mail @error('current-password') is-invalid @enderror" placeholder="Email/Username" name="login" value="{{ old('login') }}">
                
                <label class="form_label" for="current-password">Your password</label>
                <input type="password" class="form_input input-password @error('current-password') is-invalid @enderror" placeholder="••••••••••••" name="current-password">
                @if (session()->has('status'))
                    <label class="form_label_invalid"> {{ session()->get('status') }} </label>
                @endif
                @error('current-password')
                <label class="form_label_invalid">{{ $message }}</label>
                @enderror
                <button class="btn-login general-button">LOG IN</button>
            </form>
            <p class="users-link text-login">Do you not have an account?
                <a href="{{ route('register.index') }}">Register</a>
            </p>
            <p class="users-link text">Do you forgot the password?
                <a href="{{ route('login.password') }}">Remember it.</a>
            </p>
        </section>
        <section class="login-text intro-style-login">
            <a href="{{ route('product.index') }}">
                <img class="intro-style-login_logo" src="{{ asset('images/marketify_logo.png') }}">
            </a>
            <h1 class="intro-style-login_title">LOG IN TO ENJOY OUR APP!</h1>
            <p class="intro-style-login_text">Marketify is an application that allows you to buy products and sell them at the same
                time. In this way you can be a buyer and a seller at once.<br><br>By: Albert Valls, David Hernández
                & Oscar Ramírez.</p>
        </section>
    </section>

@endsection
