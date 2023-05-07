@extends('layouts.main')

@section('title', 'Orders')

@section('content')
    <h1 class="main_title">Orders</h1>
    <section class="main-orders">
        <section class="orders-card">
            <div class="product card-style-orders">
                @foreach ($shops as $shop)
                    <div class="cart-product">
                        <h4>Shop: {{ $shop->shopname }}</h4>
                        @foreach ($carts as $cart)
                            @if (count($productsByShop) > 0)
                                <div class="cart-product">
                                    @foreach ($productsByShop as $product)
                                        <p>{{ $product->name }}{{$product->id}}</p>
                                    @endforeach
                                </div>
                            @else
                                <p>No products</p>
                            @endif
                        @endforeach
                    </div>
                @endforeach

            </div>
        </section>
    </section>
    <script type="module" src="{{ asset('js/pages/cart_index.js') }}"></script>
@endsection
