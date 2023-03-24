@extends('layouts.main')

@section('title', 'Product')

@section('content')
        <img src="../../{{ $product->image }}" height="auto" width=250>
            <h1>{{ $product->name }}</h1>
            <p><b>Description: </b>{{ $product->description }}</p>
            <p><b>Price: </b>{{ $product->price }}</p>

            <button class="btn-cart" data-product-id="{{ $product->id }}">Add to cart</button>
            <script type ="module" src="{{ asset('js/pages/product_show.js') }}"></script>
@endsection
