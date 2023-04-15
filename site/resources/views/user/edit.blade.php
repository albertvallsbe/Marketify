@extends('layouts.main')

@section('title', 'Edit Profile')

@section('content')
    @if (session()->has('status'))
        <p>{{ session()->get('status') }}</p>
    @endif
    <h1>Profile page</h1>
    <section class="main-container">
        <section class="card-style-user">
            <h3 card-style-cart_title>Change your password</h3>
            <form class="form" method='POST' action="{{ route('user.changeData') }}" enctype="multipart/form-data">
                @csrf
                <label  class="form_label" for="actual-password">Your actual password:
                </label>
                <input type="password" class="form_input rememberpassw" placeholder="********" name="actual-password">

                <label  class="form_label" for="remember-password">Your new password:
                </label>
                <input type="password" class="form_input rememberpassw" placeholder="********" name="remember-password">

                <label  class="form_label" for="repeat-password">Repeat your new password:</label>
                <input type="password" class="form_input rememberpassw" placeholder="********" name="repeat-password">

            <button class="general-button btn-password" name="btn-password">CHANGE</button>
        </section>
        <section class="card-style-user">
            <h3>Change your avatar</h3>
            <span>
                <label class="form_label" for="avatar">Avatar:</label><br>
                <img src="{{ asset(Auth::user()->avatar) }}" alt="Actual avatar" width="150" height="150"><br>
                <input type="file" name="avatar" id="avatar" accept="image/*">
            </span>
            <button class="general-button btn-password" name="btn-avatar" id="btn-avatar" disabled>CHANGE</button>
            @if (Auth::user()->avatar != 'images/profiles/default-avatar.jpg')
                <button class="general-button btn-password" name="btn-avatar-rm">RESET</button>
            @endif

        </section>
        <section class="card-style-user">
            <h3>Change your actual username</h3>
            <form class="form">
                <label class="form_label" for="username">Your new username:</label>
                <input class="form_input" type="text" name="username" placeholder="{{ $user->name }}">
            </form>

            <button class="general-button btn-password" name="btn-username" id="btn-username">CHANGE</button>
        </form>
    </section>
        <a href="{{ route('user.logout') }}">Log out</a>
            <script type="module" src="{{ asset('js/pages/user_edit.js') }}"></script>
@endsection
