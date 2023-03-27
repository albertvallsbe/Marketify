@extends('layouts.main')

@section('title', 'Product')

@section('content')
    {{-- @if (session()->has('status')) --}}
        <p>{{ session()->get('status') }}</p>
    {{-- @endif --}}
    <h1>Profile page</h1>

    {{-- <form action="{{ route('user.changeData'), auth()->user()->id }}" method="GET"> --}}

    {{-- </form> --}}

    <h3>Change your password</h3>
    <form method='POST' action="{{ route('user.changeData', request()->route('id')) }}">
        @csrf
        {{-- <input type="hidden" name="id" value="{{ auth()->user()->id }}"> --}}
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
        <button class="btn-password">CHANGE</button>
    </form>
    <a href="{{ route('user.logout') }}">
        Cerrar sesión
    </a>
@endsection
