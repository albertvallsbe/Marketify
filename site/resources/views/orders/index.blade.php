@extends('layouts.main')

@section('title', 'Orders')

@section('content')
    <h1 class="main_title">Orders</h1>
    <section class="main-orders">
        <section class="orders-card">
            <div class="product card-style-orders">
                @foreach ($carts as $cart)
                    <div class="cart-product">
                        <h4>{{ $cart->id }}</h4>
                        <p>Products: {{ $cart->products }}</p>
                        @foreach ($cart as $productId)
                            <p>{{ $productId }}</p>
                        @endforeach
                        <p>Shop: {{ $cart->user_id }}</p>
                        <p>Shop Name: {{ $cart->shopname }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    </section>
    <script type="module" src="{{ asset('js/pages/cart_index.js') }}"></script>
@endsection
