@extends('layouts.main')

@section('title', 'Product')

@section('content')
  <img src="../../{{ $product->image }}" height="auto" width=250>
    <h1>{{ $product->name }}</h1>
    <p>Description: {{ $product->description }}</p>
    <p>Price: {{ $product->price }}</p>
    <button class="btn-cart" data-product-id="{{ $product->id }}">Add to cart</button>
    <script type ="module" src="{{ asset('js/pages/product_show.js') }}"></script>
@endsection
