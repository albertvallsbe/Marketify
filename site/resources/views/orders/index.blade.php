@extends('layouts.main')

@section('title', 'Orders')

@section('content')
    <h1 class="main_title">Orders</h1>
    <section class="main-orders">
        <section class="orders-card">
            <div class="product card-style-orders">
                <h2 class="products-title">name</h2>
            </div>
        </section>
    </section>
    <script type="module" src="{{ asset('js/pages/ordersIndex.js') }}"></script>
@endsection
