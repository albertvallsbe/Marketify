@extends('layouts.main')

@section('title', 'Edit Profile')

@section('content')
    @if (session()->has('status'))
        <p>{{ session()->get('status') }}</p>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <h1>Profile page</h1>
    <section class="main-edituser">
        <section class="card-style-edituser">
            <h3 class="card-style-edituser_title">Change your password</h3>
            <form class="form" method='POST' action="{{ route('user.changeData') }}" enctype="multipart/form-data">
                @csrf
                <label  class="form_label" for="current-password">Your actual password:
                </label>
                <input type="password" class="form_input rememberpassw" placeholder="••••••••••••" name="current-password">

                <label  class="form_label" for="new-password">Your new password:
                </label>
                <input type="password" class="form_input rememberpassw" placeholder="••••••••••••" name="new-password">

                <label  class="form_label" for="repeat-password">Repeat your new password:</label>
                <input type="password" class="form_input rememberpassw" placeholder="••••••••••••" name="repeat-password">

            <button class="general-button btn-password" name="btn-password">CHANGE</button>
        </section>
        <section class="card-style-edituser">
            <h3 class="card-style-edituser_title">Change your avatar</h3>
            <span>
                <label class="form_label" for="avatar">Avatar:</label><br>
                <img src="{{ asset(Auth::user()->avatar) }}" alt="Actual avatar" width="150" height="150"><br>
                <input type="file" name="avatar" id="avatar" accept="image/*">
            </span>
            <div class="btns-cart">
                <button class="general-button btn-password" name="btn-avatar" id="btn-avatar" disabled>CHANGE</button>
                @if (Auth::user()->avatar != 'images/profiles/default-avatar.jpg')
                    <button class="general-button btn-password" name="btn-avatar-rm">RESET</button>
                @endif
            </div>

        </section>
        <section class="card-style-edituser">
            <h3 class="card-style-edituser_title">Change your username :</h3>
                <h5 class="card-style-edituser_title">Current username: <i>{{ $user->name }}</i></h5>

                <label class="form_label" for="name">Your new username:</label>
                <input class="form_input" type="text" name="name" placeholder="{{ $user->name }}">
            <button class="general-button btn-password" name="btn-username" id="btn-username">CHANGE</button>
        </form>
    </section>
        <a href="{{ route('user.logout') }}">Log out</a>
            <script type="module" src="{{ asset('js/pages/user_edit.js') }}"></script>
@endsection
