@extends('layouts.main')

@section('title', 'Product')

@section('content')
    <section class="main-productshow">
        <section class="card-style-productshow-image"=>
            <img src="../../{{ $product->image }}" height="auto" width=250>
        </section>
        <section class="card-style-productshow-details">
            <h1>{{ $product->name }}</h1>
            <p>Description: {{ $product->description }}</p>
            <p>Price: {{ $product->price }}</p>
            <button class="btn-cart" data-product-id="{{ $product->id }}">Add to cart</button>

        </section>

    </section>
    <script type ="module" src="{{ asset('js/pages/product_show.js') }}"></script>
@endsection
