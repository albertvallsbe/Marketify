@extends('layouts.main')

@section('title', 'Landign Page')

@section('content')
    <section class="landing-container">
        <a href="{{route('products.index')}}">
            <div class="container-slogan">
                <img class="logo" src="{{ asset('images/marketify_logo.png') }}">
                <h1 class="title-slogan">The best products at the best price!</h1>
            </div>
        </a>
        <div class="container-products">
            <div class="container-product">
                <h2 class="title-product">producto</h2>
            </div>
            <div class="container-product2">
                <h2 class="title-product">producto</h2>
            </div>
        </div>
        @foreach ($activeTags as $tag)
            <h3>{{ $tag['name'] }}</p>
            @foreach ($tag['products'] as $tagProducts)
                <div class="card-style-home product" id={{ $tagProducts['id'] }}>
                    {{-- <a class="card-style-home_a" href="{{ route('product.show', $product->id) }}"> --}}
                        {{-- </div>
                        <div class="product__div_img"> --}}
                        <img class="product-img" src="{{$tagProducts['image']}}" />
                        <h4 class="card-style-home_title product__name">{{ $tagProducts['name'] }}</h4>
                        <h4 class="card-style-home_title product__name">{{ $tagProducts['tag'] }}</h4>
                        <h3 class="card-style-home_title product__price">{{ $tagProducts['price'] }} €</h3>
                    {{-- </a> --}}
                    <button class="btn-cart small-button" data-product-id="{{  $tagProducts['id']}}">Add to cart</button>
                    </div>
            @endforeach
        @endforeach
        <div class="products-container">
            <h2 class="products-title">Recommended products for you...</h2>

        </div>
    </section>

@endsection
