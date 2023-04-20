@extends('layouts.main')

@section('title', 'Landign Page')

@section('content')
    <section class="landing-container">
        <a href="{{route('products.index')}}">
            <div class="container-slogan">
                <img class="logo" src="{{ asset('images/marketify_logo.png') }}">
                <h1 class="title-slogan">The best products at the best price!</h1>
            </div>
        </a>
        <div class="container-products">
            <div class="container-product">
                <h2 class="title-product">producto</h2>
            </div>
            <div class="container-product2">
                <h2 class="title-product">producto</h2>
            </div>
        </div>
        <div class="products-container">
            <h2 class="products-title">Recommended products for you...</h2>
            <hr>

        </div>
    </section>

@endsection
