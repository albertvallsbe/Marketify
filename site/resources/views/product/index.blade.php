@extends('layouts.main')

@section('title', 'Marketify')

@section('content')
    <h1>Products</h1>
    <div id="products">
        @if ($products->count())
            @foreach ($products as $key => $product)
                <div class="product" id={{ $product->id }}>
                    <a href="{{ route('product.show', $product->id) }}">
                        <div class="product__div_img">
                            <img class="product__img" src="{{ $product->image }}" />
                        </div>
                        <h4 class="product__name">{{ $product->name }}</h4>
                        <h4 class="product__name">{{ $product->tag }}</h4>
                        <h3 class="product__price">{{ $product->price }} €</h3>
                    </a>
                    <button id="btn-cart">Add to cart</button>
                </div>
            @endforeach
        @else
            <h2>No results were found.</h2>
        @endif
    </div>
    <div id="pagination">
        {{-- {{ $products->appends(['search' => $search]) }} --}}
        {{ $products->links('vendor.pagination.default') }}
    </div>
@endsection
