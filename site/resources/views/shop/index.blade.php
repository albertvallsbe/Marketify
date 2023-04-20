@extends('layouts.main')

@section('title', 'Shop creator')

@section('content')
    <h1>Shop creator</h1>
    @can('seller')
        <p>You already have a shop!</p>
        <a href="../">Go back</a>
    @elsecan('shopper')
        <form method="POST" action="{{ route('shop.create') }}">
            @csrf
            <label for="store_name">Store name</label><br>
            <input type="text" name="store_name" id="store_name" required><br>
            <label for="username">Name of user</label><br>
            <input type="text" name="username" id="user_name" required><br>
            <label for="nif">NIF or DNI</label><br>
            <input type="text" name="nif" id="nif" required><br>
            <button type="submit">Submit</button>
        </form>
    @endcan

    {{-- <script type="module" src="{{ asset('js/pages/shop_creator.js') }}"></script> --}}
@endsection
