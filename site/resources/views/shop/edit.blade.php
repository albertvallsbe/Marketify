@extends('layouts.main')

@section('title', 'Shop')

@section('content')
    <h1>Shop name: {{ $shop->shopname }}</h1>
    <p><b>NAME USER: </b>{{ $shop->username }}</p>
    <p><b>NIF/DNI: </b>{{ $shop->nif }}</p>
    {{-- <script type ="module" src="{{ asset('js/pages/shop_show.js') }}"></script> --}}
@endsection
