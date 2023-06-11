@extends('layouts.main')

@section('title', 'Marketify')

@section('content')
    <section class="landing-container">
        <a href="{{ route('product.index') }}">
            <div class="container-slogan">
                <img class="logo" src="{{ asset('images/marketify_logo.png') }}">
                <h1 class="title-slogan">The best products at the best price!</h1>
            </div>
        </a>
        <div class="container-products">
            <div class="container-product">
                <h2 class="title-product">Product</h2>
            </div>
            <div class="container-product2">
                <h2 class="title-product">Product</h2>
            </div>
        </div>
        @foreach ($activeTags as $tag)
            <div class="products-container">
                @if (isset($tag['name']))
                    <a class="card-style-home_a" href="{{ route('product.filter', $tag['name']->id) }}">
                        <h2 class="products-title">{{ $tag['name']->name }}</h2>
                    </a>
                @endif
            </div>
            <div class="products-section">
                @foreach ($tag['products'] as $tagProducts)
                    <div class="card-style-home product" id={{ $tagProducts['id'] }}>
                        <a class="card-style-home_a" href="{{ route('product.show', $tagProducts['id']) }}">
                            <img class="product-img" src="{{ asset(env('API_IP').$paths[$tagProducts->id]) }}" />
                            <h4 class="card-style-home_title product__name">{{ $tagProducts['name'] }}</h4>
                            <h4 class="card-style-home_title product__name">{{ $tagProducts['tag'] }}</h4>
                            <h3 class="card-style-home_title product__price">{{ $tagProducts['price'] }} €</h3>
                        </a>
                    </div>
                @endforeach
            </div>
        @endforeach
        <div class="section-btn">
            <a href="{{ route('product.index') }}">
                <button class="btn-see-more">VIEW MORE</button>
            </a>
        </div>
    </section>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
@endsection
