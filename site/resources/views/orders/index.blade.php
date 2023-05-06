@extends('layouts.main')

@section('title', 'Orders')

@section('content')
    <h1 class="main_title">Orders</h1>
    <section class="main-orders">
        <section class="orders-card">
            <div class="product card-style-orders">
                @foreach ($cartProducts as $cartProduct)
                    <div class="cart-product">
                        <h4>{{ $cartProduct['product']->name }}</h4>
                        <p>Price: {{ $cartProduct['product']->price }}</p>
                        <p>Shop: {{ $cartProduct['shop']->name }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    </section>
    <script type="module" src="{{ asset('js/pages/ordersIndex.js') }}"></script>
@endsection
