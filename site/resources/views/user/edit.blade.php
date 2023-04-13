@extends('layouts.main')

@section('title', 'Edit Profile')

@section('content')
    @if (session()->has('status'))
        <p>{{ session()->get('status') }}</p>
    @endif
    <h1>Profile page</h1>

    <form method='POST' action="{{ route('user.changeData') }}" enctype="multipart/form-data">
        @csrf
        <h3>Change your password</h3>
        <span>
            <label for="actual-password">Your actual password</label><br>
            <input type="password" class="rememberpassw" placeholder="********" name="actual-password">
            <br>
        </span>
        <span>
            <label for="remember-password">Your new password</label><br>
            <input type="password" class="rememberpassw" placeholder="********" name="remember-password">
            <br>
        </span>
        <span>
            <label for="repeat-password">Repeat your new password</label><br>
            <input type="password" class="rememberpassw" placeholder="********" name="repeat-password">
            <br>
        </span>
        <button class="btn-password" name="btn-password">CHANGE</button>
        <h3>Change your avatar</h3>
        <span>
            <label for="avatar">Avatar:</label><br>
            <img src="{{ asset(Auth::user()->avatar) }}" alt="Actual avatar" width="150" height="150"><br>
            <input type="file" name="avatar" id="avatar" accept="image/*"><br>
        </span>
        <button class="btn-password" name="btn-avatar" id="btn-avatar" disabled>CHANGE</button>
        @if (Auth::user()->avatar != 'images/profiles/default-avatar.jpg')
            <button class="btn-password" name="btn-avatar-rm">RESET</button>
        @endif
        <br>
        <h3>Change your username :</h3>
        <span>
            <label for="username">Change your username </label>
            <br>
            <input type="text" name="username" placeholder="_.username._">
        </span>
        <br>
        <button class="btn-password" name="btn-username" id="btn-username">CHANGE</button>
    </form>

    <a href="{{ route('user.logout') }}">Log out</a>

    <script type="module" src="{{ asset('js/pages/user_edit.js') }}"></script>
@endsection
