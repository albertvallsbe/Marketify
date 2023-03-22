@extends('layouts.main')

@section('title', 'Cart')

@section('content')
    <section class="container">
        <div class="section">
            <h1>Cart</h1>
        </div>
        <div class="section summary">
            <h1 class="title">Summary</h1>
            <hr>
            <div class="products-cart">

                <h3 class="total">Total: €</h3>
            </div>
            <div class="btns-cart">
                <button class="btn-empty">EMPTY CART</button>
                <button class="btn-buy">BUY ALL</button>
            </div>
        </div>
    </section>
@endsection
