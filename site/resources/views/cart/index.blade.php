@extends('layouts.main')

@section('title', 'Cart')

@section('content')
    @php
        $errorSold = false;
        $errorHidden = false;
        $errorYours = false;
    @endphp
    <h1 class="main_title">Cart</h1>
    <section class="main-cart">
        <section class="products-cart">
            @if (count($products) != 0)
                @foreach ($products as $key => $product)
                    @php
                        $isShopDifferent = (!$shop || $shop->id != $product->shop_id) && $product->status == 'active';
                        if (!$isShopDifferent) {
                            if ($product->status == 'sold') {
                                $errorSold = true;
                            }else if ($product->status == 'hidden'){
                                $errorHidden = true;
                            } else {
                                $errorYours = true;
                            }
                        }
                    @endphp
                    <div class="product card-style-cart {{ $isShopDifferent ? '' : 'hidden' }}" id="{{ $product->id }}">
                        <a class="card-style-cart_a" href="{{ route('product.show', $product->id) }}">
                            <img class="product-img" src="{{ asset(env('API_IP').$paths[$product->id])}}"/>
                            <h4 class="card-style-cart_title product__name">{{ $product->name }}</h4>
                            <h3 class="card-style-cart_title product__price">{{ $product->price }} €</h3>
                        </a>
                        <button class="btn-remove small-button" data-product-id="{{ $product->id }}">
                            {{ $isShopDifferent ? 'X' : 'Remove from cart' }}
                        </button>
                    </div>
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
                    class="btn-buy general-button general-button_buy{{ count($products) == 0 || !auth()->user() || $errorSold || $errorYours ? ' btn-disabled' : '' }}"{{ count($products) == 0 || !auth()->user() || $errorSold || $errorYours ? ' disabled' : '' }}>
                    CHECKOUT
                </button>
            </div>
            @if (!auth()->user())
                <i><a href="{{ route('login.index') }}">Log in</a> to buy on our website.</i>
            @endif
            @if ($errorSold)
                <p>Some products have been sold. Please delete them to proceed.</p>
            @endif
            @if ($errorHidden)
                <p>Some products are marked as disabled by their owner. Please delete them to proceed.</p>
            @endif

            @if ($errorYours)
                <p>Some products are marked as if you are the owner. Please delete them to proceed.</p>
            @endif
        </section>
    </section>
    <script type="module" src="{{ asset('js/pages/cart_index.js') }}"></script>
@endsection
