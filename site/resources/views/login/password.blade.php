@extends('layouts.users')

@section('title', 'Forgot password')

@section('content')
  <section class="main-container">
    <section class="card-style section-content">
      <h1 class="card-style_title password-title">Have you forgotten the password?</h1>
      <p class="card-style_content">Have you forgotten the password? Don't worry. Enter your email and we will send you an email to recover your password.
          <br>If you want to return press
          <a href={{route('login.index')}}>Login</a>
      </p>
      <form class="form" method='POST'action="{{ route('login.remember') }}">
        @csrf
        <input type="email" class="form_input input-mail-password" placeholder="example@gmail.com" name="remember-password">
        <button class="general_button btn-password">SEND EMAIL</button>
        @if ($errors->has('login-email'))
          <span>{{ $errors->first('login-email') }}</span>
        @endif
        @if(session()->has('status'))
          <p> {{ session()->get('status') }} </p>
        @endif
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
@endsection
