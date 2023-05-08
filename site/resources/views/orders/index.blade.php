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
        </section>
    </section>
    <script type="module" src="{{ asset('js/pages/order_index.js') }}"></script>
@endsection
