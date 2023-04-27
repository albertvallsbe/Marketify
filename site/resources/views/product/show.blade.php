@extends('layouts.main')

@section('title', $product->name)

@section('content')
    <section class="main-productshow">
        <section class="card-style-productshow"=>
            <img class="card-style-productshow_image" src="{{ asset($product->image) }}" height="auto" width="500px">
        </section>
        <section class="card-style-productshow-details">
            <h1 class="card-style-productshow-details_title">{{ $product->name }}</h1>
            <p class="card-style-productshow-details_content">Description: {{ $product->description }}</p>
            {{-- <p class="card-style-productshow-details_content">Category: {{ $categoryname }}</p> --}}
            <p class="card-style-productshow-details_content">Shop: {{ $shopname }}</p>
            <p class="card-style-productshow-details_content">Price: {{ $product->price }}</p>
            <button class="small-button btn-cart" data-product-id="{{ $product->id }}">Add to cart</button>
        </section>
    </section>
    <script type ="module" src="{{ asset('js/pages/product_show.js') }}"></script>
@endsection
