@extends('layouts.users')

@section('title', 'Forgot the password')

@section('content')
    <section class="main-container">
        <section class="card-style-new-password remember-section remember-container">
            <h1 class="card-style-new-password_title remember-title">Have you forgotten the password?</h1>
            <p class="card-style-new-password_content remember-content">Have you forgotten the password? Don't worry. Enter your new password and we will update you new password.
                <br><br>If you want to return press
                <a href={{route('login.index')}}>Login</a>
            </p>
            <form class="form" method='POST'action="{{ route('login.rememberpassw') }}">
                @csrf
                <label class="form_label" for="remember-password">Your email</label>
                <input type="email" class="form_input rememberpassw" placeholder="Introduce your email" name="email">
                <label class="form_label" for="remember-password">Your new password</label>
                <input type="password" class="form_input rememberpassw" placeholder="Introduce your new password" name="remember-password">
                <label class="form_label" for="remember-password">Repeat your new password</label>
                <input type="password" class="form_input rememberpassw" placeholder="Repeat your new passsword" name="repeat-password">

                <button class="general-button btn-password">UPDATE PASSWORD</button>
            </form>
        </section>
        <section class="login-text intro-style">
            <a href="{{ route('product.index') }}">
            <img class="intro-style_logo" src="{{ asset('images/marketify_logo.png') }}">
            </a>
            <h1 class="intro-style_title">LOG IN TO ENJOY OUR APP!</h1>
            <p class="intro-style_text">This is an application that allows you to buy products and sell them at the same
                time. In this way you can be a buyer and a seller at the same time.<br><br>By: Albert Valls, David Hernández
                & Oscar Ramírez.</p>
        </section>
    </section>
</body>

</html>
