@extends('layouts.main')

@section('title', 'Marketify')

@section('content')
<div id="filters"></div>
    <div id="products">
        @foreach ($products as $key => $product)
            <div class="product">
                <a href="{{ route('product.show', $product->id) }}">
                {{-- <img src="{{ $product->image }}"/> --}}
                <img class="product_img" src="https://www.ikea.com/es/es/images/products/bergmund-silla-efecto-roble-hallarp-beige__0926594_pe789377_s5.jpg"/>

                <h3>{{ $product->name }}</h3>
                <h4>{{ $product->price }} €</h4>
                </a>
            </div>
        @endforeach
    </div>
    <div id="pagination">
    {{ $products->appends(['search' => $search]) }}
    </div>
@endsection
