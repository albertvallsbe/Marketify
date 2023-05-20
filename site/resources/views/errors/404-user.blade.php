@extends('layouts.main')

@section('title', 'User not found')

@section('content')
    <h1>User not found</h1>
    <h4>This user does not exist :(</h4>
    <a href="{{ route('product.index') }}">See other pages</a>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
@endsection
