@extends('layouts.users')

@section('title', 'Register')

@section('content')
  <section class="main-container">
    <section class="card-style">
      <h1 class="card-style_title register-title">Register</h1>
      <form class="form" method="POST" action="{{route('auth.register')}}">
        @csrf
        <input type="email" class="form_input input-mail" placeholder="example@gmail.com" name="register-email">
        <input type="text" class="form_input input-mail" placeholder="_.username._" name="register-username">
        <input type="password" class="form_input input-password" placeholder="••••••••••••" name="register-password">
        <button class="btn-register general_button">CREATE ACCOUNT</button>
      </form>
      <p class="text-register users-link">Do you already have an account?
        <a href="{{ route('login.index') }}">Login</a>
      </p>
    </section>
    <section class="register-text  intro-style">
      <a href="{{ route('product.index') }}">
        <img class="intro-style_logo" src="{{ asset('images/marketify_logo.png') }}">
      </a>
      <h1 class="intro-style_title">REGISTER TO ENJOY OUR APP!</h1>
      <p class="intro-style_text">This is an application that allows you to buy products and sell them at the same
          time. In this way you can be a buyer and a seller at the same time.<br><br>By: Albert Valls, David Hernández & Oscar Ramírez.</p>
    </section>
  </section>
@endsection
