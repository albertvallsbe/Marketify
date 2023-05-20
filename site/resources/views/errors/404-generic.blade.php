@extends('layouts.main')

@section('title', 'Page not found')

@section('content')
    <h1>Page not found</h1>
    <h4>This page does not exist :(</h4>
    <a href="{{ route('product.index') }}">See other pages</a>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
@endsection
