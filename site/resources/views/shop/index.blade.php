@extends('layouts.main')

@section('title', 'Shop creator')

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
    <h1>Shop creator</h1>
    @can('seller')
        <p>You already have a shop!</p>
        <a href="../">Go back</a>
    @elsecan('shopper')
        <form method="POST" action="{{ route('shop.create') }}" enctype="multipart/form-data">
            @csrf
            <label for="storename">Store name</label><br>
            <input type="text" name="storename" id="storename" required><br>
            <label for="username">Name of user</label><br>
            <input type="text" name="username" id="username" required><br>
            <label for="nif">NIF or DNI</label><br>
            <input type="text" name="nif" id="nif" required><br>
            <label class="form_label" for="image">Logo:</label><br>
            <input type="file" name="image" id="image" accept="image/*"><br>
            <button type="submit">Submit</button>
        </form>
    @endcan

    {{-- <script type="module" src="{{ asset('js/pages/shop_creator.js') }}"></script> --}}
@endsection
