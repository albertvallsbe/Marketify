@extends('layouts.users')

@section('title', 'Remember password')

@section('content')
    <section class="container">
        <section class="section-container">
            <section class="section-content">
                <h1 class="password-title">Have you forgotten the password?</h1>
                <p class="content">Have you forgotten the password? Don't worry. Enter your email and we will send you an
                    email to recover your password.
                    <br><br>If you want to return press <a href={{ route('login.rememberView') }} name="link">Remember
                        password</a>
                </p>
            </section>
        </section>
    </section>
@endsection