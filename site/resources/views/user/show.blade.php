@extends('layouts.main')

@section('title', 'Profile')

@section('content')
    <h1>Profile page</h1>
    <p><b>Name:</b> {{ $user->name }}</p>
    <p><b>Email:</b> {{ $user->email }}</p>
    {{-- <script type="module" src="{{ asset('js/pages/user_show.js') }}"></script> --}}
@endsection