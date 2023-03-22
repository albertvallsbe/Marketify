@extends('layouts.main')

@section('title', 'Log In')

@section('content')
    <section class="main-container">
        <section class="container-login">
            <section class="section-content">
                <h1 class="login-title">Log in</h1>
                <hr>
                <form method='POST'action="{{ route('auth.login') }}">
                    @csrf
                    <input type="email" class="input-mail" placeholder="example@gmail.com" name="email">
                    @if ($errors->has('login-email'))
                        <span><strong>{{ $errors->first('login-email') }}</strong></span>
                    @endif
                    <input type="password" class="input-password" placeholder="••••••••••••" name="password">
                    @if ($errors->has('login-password'))
                        <span><strong>{{ $errors->first('login-password') }}</strong></span>
                    @endif
                    @if(session()->has('status'))
                    
                        <p> {{ session()->get('status') }} </p>
                    
                    @endif
                    <p class="text-login">You do not have an account? <a href="{{ route('register.index') }}">Create one</a>
                    </p>

                    <button class="btn-login">LOG IN</button>

                </form>
            </section>
        </section>
        <section class="login-text">
            <a href="{{ route('product.index') }}">
                <img class="img-logo" src="{{ asset('images/marketify_logo.png') }}">
            </a>
            <h1 class="login-title">LOG IN TO ENJOY OUR APP!</h1>
            <p class="text-login">This is an application that allows you to buy products and sell them at the same
                time. In this way you can be a buyer and a seller at the same time.<br><br>By: Albert Valls, David Hernández
                & Oscar Ramírez.</p>

        </section>
    </section>

@endsection
