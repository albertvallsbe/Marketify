@extends('layouts.main')

@section('title', 'Orders')

@section('content')
    <h1 class="main_title">Orders</h1>
    <section class="main-orders">
        <h3>One or more products have been purchased while you were placing the order.</h3>
    </section>
    <script type="module" src="{{ asset('js/pages/order_index.js') }}"></script>
@endsection
