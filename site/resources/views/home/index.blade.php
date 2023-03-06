@extends('layouts.main') 

@section('title','Index')

@section('content')
<h1>Index</h1>


{{-- @foreach ($products as $key => $product) --}}

    {{-- <a href="{{ route('catalog.show', $product->id) }}"> --}}
        {{-- <img src="{{ $product->image }}" /> --}}
        <h4>
            {{-- {{ $product->title }} --}}
            H4
        </h4>
    {{-- </a> --}}

{{-- @endforeach --}}

@endsection