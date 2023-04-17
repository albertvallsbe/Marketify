@extends('layouts.users')

@section('title', 'Log In')

@section('content')
    <section class="main-container">
        <section class="card-style-medium">
            <h1 class="card-style-medium_title login-title">Login</h1>
            <form class="form" method='POST'action="{{ route('auth.login') }}">
                @csrf
                <label class="form_label" for="login">Your email/username</label>
                <input type="text" class="form_input input-mail" placeholder="Email/Username" name="login">
                @if ($errors->has('login-email'))
                    <span>{{ $errors->first('login-email') }}</span>
                @endif
                <label class="form_label" for="current-password">Your password</label>
                <input type="password" class="form_input input-password" placeholder="••••••••••••" name="current-password">
                @if ($errors->has('login-password'))
                    <span>{{ $errors->first('login-password') }}</span>
                @endif
                @if (session()->has('status'))
                    <p class="text-login"> {{ session()->get('status') }} </p>
                @endif
                <button class="btn-login general-button">LOG IN</button>
            </form>
            <p class="users-link text-login">Do you not have an account?
                <a href="{{ route('register.index') }}">Register</a>
            </p>
            <p class="users-link text">Do you forgot the password?
                <a href="{{ route('login.password') }}">Remember it.</a>
            </p>
        </section>
        <section class="login-text intro-style">
            <a href="{{ route('product.index') }}">
                <img class="intro-style_logo" src="{{ asset('images/marketify_logo.png') }}">
            </a>
            <h1 class="intro-style_title">LOG IN TO ENJOY OUR APP!</h1>
            <p class="intro-style_text">Marketify is an application that allows you to buy products and sell them at the same
                time. In this way you can be a buyer and a seller at once.<br><br>By: Albert Valls, David Hernández
                & Oscar Ramírez.</p>
        </section>
    </section>

@endsection
