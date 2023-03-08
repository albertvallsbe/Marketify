@extends('layouts.main')

@section('title', 'Marketify')

@section('content')
<div id="filters"></div>
    <div id="products">
        @foreach ($products as $key => $product)
            <div class="product">
                <a href="{{ route('product.show', $product->id) }}">
                {{-- <img src="{{ $product->image }}"/> --}}
                <img class="product__img" src="{{ $product->image }}"/>

                <h4 class="product__name" >{{ $product->name }}</h4>
                <h3 class="product__price" >{{ $product->price }} €</h3>
                </a>
            </div>
        @endforeach
    </div>
    <div id="pagination">
    {{ $products->appends(['search' => $search]) }}
    </div>
@endsection
