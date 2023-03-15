@extends('layouts.main')

@section('title', 'Marketify')

@section('content')
    <h1>Products</h1>
    <div id="products">
        @if ($products->count())
            @foreach ($products as $key => $product)
                <div class="product" id={{ $product->id }}>
                    <a href="{{ route('product.show', $product->id) }}">
                        <img class="product__img" src="{{ $product->image }}" />
                        <h4 class="product__name">{{ $product->name_product }}</h4>
                        <h4 class="product__name">{{ $product->tag }}</h4>
                        <h3 class="product__price">{{ $product->price }} €</h3>
                    </a>
                    <button id="btn-cart">xdd</button>
                </div>
            @endforeach
        @else
            <p>No se encontraron resultados.</p>
        @endif
    </div>
    <div id="pagination">
        {{-- COMENTADA LA FUNCION APPEND --}}
        {{-- {{ $products->appends(['search' => $search]) }} --}}
    </div>
@endsection
