@extends('layouts.main') 

@section('title', 'Product')

@section('content')
        <img src="{{ $product->image }}">
            <h1>{{ $product->name }}</h1>
            <p><b>Description: </b>{{ $product->description }}</p>
            <p><b>Price: </b>{{ $product->price }}</p>
@endsection