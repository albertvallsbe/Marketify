@extends('layouts.main')

@section('title', 'Shop creator')

@section('content')
    <h1>Shop creator</h1>

    <form method="POST" action="{{ route('shop.create') }}">
        @csrf
        <label for="store_name">Store name</label><br>
        <input type="text" name="name" id="store_name" required><br>
        <label for="nif">NIF or DNI</label><br>
        <input type="text" name="nif" id="nif" required><br>
        <button type="submit">Submit</button>
    </form>
    {{-- <script type="module" src="{{ asset('js/pages/shop_creator.js') }}"></script> --}}
@endsection
