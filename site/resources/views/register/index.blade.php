@extends('layouts.users')

@section('title', 'Register')

@section('content')
@if($errors->any())
  <div class="alert alert-error">
    @foreach($errors->all() as $error)
      <p>{{ $error }}</p>
    @endforeach
  </div>
@endif
  <section class="main-container">
    <section class="card-style-medium">
      <h1 class="card-style-medium_title register-title">Register</h1>
      <form class="form" method="POST" action="{{route('auth.register')}}">
        @csrf
        <label class="form_label" for="email">Your email</label>
        <input type="email" class="form_input input-mail" placeholder="example@gmail.com" name="email">
        <label class="form_label" for="name">Your username</label>
        <input type="text" class="form_input input-mail" placeholder="username" name="name">
        <label class="form_label" for="password">Your password</label>
        <input type="password" class="form_input input-password" placeholder="••••••••••••" name="password">
        <button class="btn-register general-button">CREATE ACCOUNT</button>
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
      <p class="intro-style_text">Marketify is an application that allows you to buy products and sell them at the same
          time. In this way you can be a buyer and a seller at once.<br><br>By: Albert Valls, David Hernández & Oscar Ramírez.</p>
    </section>
  </section>
@endsection
