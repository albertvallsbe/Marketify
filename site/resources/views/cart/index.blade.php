@extends('layouts.main')

@section('title', 'Cart')

@section('content')
    <section class="container">
        <div class="section">
            <h1>Cart</h1>
            @if (count($products) != 0)
                @foreach ($products as $key => $product)
                    <div class="product" id={{ $product->id }}>
                        <a href="{{ route('product.show', $product->id) }}">
                            <div class="product__div_img">
                                <img class="product__img" src="{{ $product->image }}" />
                            </div>
                            <h4 class="product__name">{{ $product->name }}</h4>
                            <h3 class="product__price">{{ $product->price }} €</h3>
                        </a>
                        <button class="btn-remove" data-product-id="{{ $product->id }}">X</button>
                    </div>
                @endforeach
            @else
                <h2>No results were found.</h2>
            @endif
        </div>
        <div class="section summary">
            <h1 class="title">Summary</h1>
            <hr>
            <div class="products-cart">

                <div class="individual_prices"></div>
                <h3 class="total">Total: €</h3>
            </div>
            <div class="btns-cart">
                <button class="btn-empty">EMPTY CART</button>
                <button class="btn-buy">BUY ALL</button>
            </div>
        </div>
    </section>
    <script type="module" src="{{ asset('js/pages/cart_index.js') }}"></script>
@endsection
