@extends('layouts.users')

@section('title', 'Log In')

@section('content')
  <section class="main-container">
    <section class="container-login">
      <section class="card_style">
        <h1 class="login-title">Login</h1>
        <form method='POST'action="{{ route('auth.login') }}">
          @csrf
          <input type="email" class="input-mail" placeholder="example@gmail.com" name="email">
          <input type="text" class="input-mail" placeholder="_.username._" name="name">
          @if ($errors->has('login-email'))
            <span><strong>{{ $errors->first('login-email') }}</strong></span>
          @endif
          <input type="password" class="input-password" placeholder="••••••••••••" name="password">
          @if ($errors->has('login-password'))
            <span><strong>{{ $errors->first('login-password') }}</strong></span>
          @endif
          @if(session()->has('status'))
            <p class="text-login"> {{ session()->get('status') }} </p>
          @endif
          <button class="general_button">LOG IN</button>

          <p class="text-login users-link">Do you not have an account? <a href="{{ route('register.index') }}">Register</a>
          </p>
          <p class="text users-link">Do you forgot the password? <a href="{{(route('login.password')) }}">Remember it.</a>
          </p>

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
