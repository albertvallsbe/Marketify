@extends('layouts.main')

@section('title', 'Historical Details')

@section('content')
    <div class="historical-container">
        <div class="title-container">
            <h1 class="historical-title">Historical Details Order</h1>
        </div>
        <form action="" method="POST">
            @csrf
            <div class="ticket-container">
                <div class="product-info">
                    <div class="title-ticket">
                        <h2 class="ticket-title">ORDER TICKET</h2>
                    </div>
                    <div class="shop-ticket">
                        @foreach ($shop as $item)
                            <h3 class="shop-title">{{ $item->shopname }}</h2>
                        @endforeach
                    </div>
                    @foreach ($products as $orderItem)
                        <div class="order-products">
                            <div class="products-tickets">
                                <h4>{{ strtoupper($orderItem->name) }}</h4>
                                <p class='price-product'>{{ $orderItem->price }} €</p>
                            </div>
                        </div>
                    @endforeach
                    <div class="total-container">
                        <h3>Total : </h3>
                    </div>
                </div>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn-download" name="btn-download" id="btn-download">DOWNLOAD</button>
            </div>
        </form>
    </div>

@endsection
