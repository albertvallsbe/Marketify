@extends('layouts.users')

@section('title', 'Confirm paswsword')

@section('content')
    <h2> Use this token to verify: your count.</h2><br>

    <p>Press the button to confirm your count.</p><br>
    <a href="{{ route('login.index') }}"><button class="btn-confirm" name="btn-confirm">CONFIRM</button></a>
@endsection