@extends('layouts.main')

@section('title', 'Shop')

@section('content')
            <h1>{{ $shop->name }}</h1>
            <p><b>NIF/DNI: </b>{{ $shop->nif }}</p>
            {{-- <script type ="module" src="{{ asset('js/pages/shop_show.js') }}"></script> --}}
@endsection
