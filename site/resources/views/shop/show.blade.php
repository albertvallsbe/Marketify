@extends('layouts.main')

@section('title', $shop->shopname)

@section('content')
<div style="background-color: {{ $background_color }}">
    <section class="headboard" style="background-color: {{ $header_color }}">
        <div class="headboard_logo_and_name">
            <img class="headboard_shoplogo" src="{{ asset($shop->logo) }}" width=150 height=150 />
            <h1 class="headboard_shopname headboard_label">{{ $shop->shopname }}</h1>
        </div>
        <div class="headboard_info">
            <p class="headboard_username headboard_label"><b>Owner: </b>{{ $shop->username }}</p>
        </div>
    </section>
    <h1 class="main_title">Products</h1>
    <div id="products" class="products">
        @if ($products->count())
            @foreach ($products as $key => $product)
                @if (!$product->hidden)
                    <div class="card-style-home product" id={{ $product->id }}>
                        <a class="card-style-home_a" href="{{ route('product.show', $product->id) }}">
                            <img class="product-img" src="{{ asset($product->image) }}" />
                            <h4  class="card-style-home_title product__name">{{ $product->name }}</h4>
                            <h5 class="card-style-home_title product__description">{{ $product->description }}</h5>
                            <h3 class="card-style-home_title product__price">{{ $product->price }} €</h3>
                        </a>
                        @if ($shop->id != $usersShop)
                            <button class="btn-cart small-button" data-product-id="{{ $product->id }}">Add to
                                cart</button>
                        @else
                        <button class="btn-cart small-button btn-disabled" disabled>Add to cart</button>
                        @endif
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
    <div id="pagination_container">
        {{-- {{ $products->appends(['search' => $search]) }} --}}
        {{ $products->links('vendor.pagination.default') }}
    </div>
</div>
    <script type="module" src="{{ asset('js/pages/shop_show.js') }}"></script>
@endsection
