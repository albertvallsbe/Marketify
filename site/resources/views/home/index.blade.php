@extends('layouts.main')

@section('title', 'Marketify')

@section('content')
    <div id="products">
        @if ($products->count())
            @foreach ($products as $key => $product)
                <div class="product">
                    <a href="{{ route('product.show', $product->id) }}">
                        <img class="product__img" src="{{ $product->image }}" />
                        <h4 class="product__name">{{ $product->name }}</h4>
                        <h3 class="product__price">{{ $product->price }} €</h3>
                    </a>
                </div>
            @endforeach
        @else
            <p>No se encontraron resultados.</p>
        @endif
    </div>
    <div id="pagination">
        {{ $products->appends(['search' => $search]) }}
    </div>
@endsection
