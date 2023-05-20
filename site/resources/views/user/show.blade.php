@extends('layouts.main')

@section('title', $user->name)

@section('content')
    <h1>Profile page</h1>
    <p><b>Name:</b> {{ $user->name }}</p>
    <p><b>Email:</b> {{ $user->email }}</p>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
@endsection
