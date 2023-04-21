@extends('layouts.main')

@section('title', 'Shop')

@section('content')
    <h1>{{ $shop->shopname }}</h1>
    <img src="../{{ $shop->logo }}" width=150 height=150/>
    <p>{{ $shop->username }}</p>
    <p><b>NIF/DNI: </b>{{ $shop->nif }}</p>

    <h1 class="main_title">Products</h1>
    <div id="products" class="products">
        @if ($products->count())
            @foreach ($products as $key => $product)
            @if (!$product->hidden)            
                <div class="card-style-mini product" id={{ $product->id }}>
                    <a class="card-style-mini_a" href="{{ route('product.show', $product->id) }}">
                        <div class="product__div_img">
                            <img class="product__img" src="../{{ $product->image }}" />
                        </div>
                        <h4 class="card-style-mini_title product__name">{{ $product->name }}</h4>
                        <h4 class="card-style-mini_title product__name">{{ $product->tag }}</h4>
                        <h3 class="card-style-mini_title product__price">{{ $product->price }} €</h3>
                    </a>
                    <button class="btn-cart small-button" data-product-id="{{ $product->id }}">Add to cart</button>
                </div>
            @endif
            @endforeach
        @else
            @if (session('request_search') != null)
                <h2>No results were found with '<?= session('request_search') ?>'.</h2>
            @else
                <h2>No results were found.</h2>
            @endif
        @endif
    </div>
    <div id="pagination">
        {{-- {{ $products->appends(['search' => $search]) }} --}}
        {{ $products->links('vendor.pagination.default') }}
    </div>
    {{-- <script type ="module" src="{{ asset('js/pages/shop_show.js') }}"></script> --}}
@endsection
