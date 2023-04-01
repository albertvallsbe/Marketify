@extends('layouts.users')

@section('title', 'Register')

@section('content')
  <section class="main-container">
    <section class="container-register">
      <section class="card_style">
        <h1 class="register-title">Register</h1>
        <form method="POST" action="{{route('auth.register')}}">
            @csrf
            <input type="email" class="input-mail" placeholder="example@gmail.com" name="register-email">
            <input type="text" class="input-mail" placeholder="_.username._" name="register-username">
            <input type="password" class="input-password" placeholder="••••••••••••" name="register-password">
            <button class="general_button">CREATE ACCOUNT</button>

            <p class="text-register users-link">Do you already have an account?<a href="{{ route('login.index') }}">Login</a></p>

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
@endsection
