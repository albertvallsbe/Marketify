@extends('layouts.main')

@section('title', 'Orders')

@section('content')
    <h1 class="main_title">Orders</h1>
    <section class="main-orders">
        <section class="orders-card">
            <div class="product card-style-orders">
                @foreach ($productsByShop as $key => $shopByProduct)
                    <h2>{{ $shopName[$key] }}</h2>
                    <ul>
                    @foreach ($shopByProduct as $productOrder )
                        <li>{{ $productOrder->name }}, {{ $productOrder->price }} €</li>
                    @endforeach
                    </ul>
                @endforeach
            </div>
            <div class="btns-cart">
                <button class="btn-order general-button general-button_order
                    @if(count($products) == 0 || !auth()->user()) btn-disabled @endif "
                    @if(count($products) == 0 || !auth()->user()) disabled @endif>ORDER NOW
                </button>
            </div>
            @if(!auth()->user())
                <i><a href="/login">Log in</a> to buy in our website.</i>
            @endif
        </section>
    </section>
    <script type="module" src="{{ asset('js/pages/orders_index.js') }}"></script>
@endsection
