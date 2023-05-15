@extends('layouts.main')

@section('title', 'Cart')

@section('content')
    @php
        $errorSold = false;
        $errorYours = false;
    @endphp
    <h1 class="main_title">Cart</h1>
    <section class="main-cart">
        <section class="products-cart">
            @if (count($products) != 0)
                @foreach ($products as $key => $product)
                    @if ((!$shop || $shop->id != $product->shop_id) && $product->status == 'active')
                        <div class="product card-style-cart" id="{{ $product->id }}">
                            <a class="card-style-cart_a" href="{{ route('product.show', $product->id) }}">
                                <img class="product-img" src="{{ $product->image }}" />
                                <h4 class="card-style-cart_title product__name">{{ $product->name }}</h4>
                                <h3 class="card-style-cart_title product__price">{{ $product->price }} €</h3>
                            </a>
                            <button class="btn-remove small-button" data-product-id="{{ $product->id }}">X</button>
                        </div>
                    @else
                        @php
                            if ($product->status == 'sold') {
                                $errorSold = true;
                            } else {
                                $errorYours = true;
                            }
                        @endphp
                        <div class="product card-style-cart hidden" id="{{ $product->id }}">
                            <a class="card-style-cart_a" href="{{ route('product.show', $product->id) }}">
                                <img class="product-img" src="{{ $product->image }}" />
                                <h4 class="card-style-cart_title product__name">{{ $product->name }}</h4>
                                <h3 class="card-style-cart_title product__price">{{ $product->price }} €</h3>
                            </a>
                            <button class="btn-remove small-button" data-product-id="{{ $product->id }}">Remove from
                                cart</button>
                        </div>
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
                <button
                    class="btn-buy general-button general-button_buy
                    @if (count($products) == 0 || !auth()->user() || $errorSold || $errorYours) btn-disabled @endif "
                    @if (count($products) == 0 || !auth()->user() || $errorSold || $errorYours) disabled @endif>CHECKOUT
                </button>
            </div>
            @if (!auth()->user())
                <i><a href="{{ route('login.index') }}">Log in</a> to buy in our website.</i>
            @endif
            @if ($errorSold)
                <p>Some products have been sold. Please delete them to proceed.</p>
            @endif
            @if ($errorYours)
                <p>Some products are marked as if you are the owner. Please delete them to proceed.</p>
            @endif
        </section>
    </section>
    <script type="module" src="{{ asset('js/pages/cart_index.js') }}"></script>
@endsection
