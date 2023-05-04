@extends('layouts.main')

@section('title', 'Cart')

@section('content')
    <h1 class="main_title">Cart</h1>
    <section class="main-cart">
        <section class="products-cart">
            @if (count($products) != 0)
                @foreach ($products as $key => $product)
                    @if (!$shop || $shop->id != $product->shop_id)
                        <div class="product card-style-cart" id={{ $product->id }}>
                            <a class="card-style-cart_a" href="{{ route('product.show', $product->id) }}">
                                <img class="product-img" src="{{ $product->image }}" />
                                <h4 class="card-style-cart_title product__name">{{ $product->name }}</h4>
                                <h3 class="card-style-cart_title product__price">{{ $product->price }} €</h3>
                            </a>
                            <button class="btn-remove small-button" data-product-id="{{ $product->id }}">X</button>
                        </div>
                    @else
                        @php
                            $error = true;
                        @endphp
                    @endif
                @endforeach
            @else
                <h2>No results were found.</h2>
            @endif
        </section>
        <section class="card-style-account section summary">
            <h1 class="card-style-acount_title title">Summary</h1>
            <div class="users-link products-cartt">

                <div class="individual_prices"></div>
                <h3 class="total">Total: €</h3>
            </div>
            <div class="btns-cart">
                <button class="btn-empty general-button">EMPTY CART</button>
                <button class="btn-buy general-button general-button_buy">GENERATE ORDER</button>
            </div>
        </section>
    </section>
    @if ($error)
        <p>Some products has been removed from your cart as you are the owner.</p>
    @endif
    <script type="module" src="{{ asset('js/pages/cart_index.js') }}"></script>
@endsection
