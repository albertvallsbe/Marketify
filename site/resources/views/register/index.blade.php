{{-- @extends('layouts.main')

@section('title', 'Register')

@section('content')
<h1>Register</h1>
@endsection --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="..\..\css\app.css">
    <title>Register</title>
</head>

<body>
    <section class="main-container">
        <section class="container-register">
            <section class="section-content">
                <h1 class="register-title">Register</h1>
                <hr>
                <form method="POST" action="{{route('auth.register')}}">
                    @csrf
                    <input type="email" class="input-mail" placeholder="example@gmail.com" name="register-email">
                    <input type="password" class="input-password" placeholder="••••••••••••" name="register-password">
                    <p class="text-register">Do you already have an account?<a href="{{ route('login.index') }}">Log
                            in</a></p>

                    <button class="btn-register">CREATE ACCOUNT</button>

                </form>
            </section>
        </section>
        <section class="register-text">
            <a href="{{ route('product.index') }}">
                <img class="img-logo" src="{{ asset('images/marketify_logo.png') }}">
            </a>
            <h1 class="register-title">REGISTER TO ENJOY OUR APP!</h1>
            <p class="text-login">This is an application that allows you to buy products and sell them at the same
                time. In this way you can be a buyer and a seller at the same time.<br><br>By: Albert Valls, David Hernández & Oscar Ramírez.</p>
        </section>
    </section>

</body>

</html>
