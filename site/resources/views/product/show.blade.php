@extends('layouts.main') 

@section('title', 'Product')

@section('content')
        <img src="{{ $product->image }}">
            <h1>{{ $product->name }}</h1>
            <p><b>Description: </b>{{ $product->description }}</p>
            <p><b>Price: </b>{{ $product->price }}</p>

            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $product->image }}" name="product_image">
                <input type="hidden" value="{{ $product->name }}" name="product_name">
                <input type="hidden" value="{{ $product->description }}" name="product_description">
                <input type="hidden" value="{{ $product->price }}" name="product_price">
                <button type="submit">Añadir al carrito</button>
            </form>
@endsection