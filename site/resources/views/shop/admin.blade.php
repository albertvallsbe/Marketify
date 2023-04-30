@extends('layouts.main')

@section('title', 'Administrate your shop')

@section('content')
    <section class="headboard" style="background-color: {{ $header_color }}">
        <div class="headboard_logo_and_name">
            <img class="headboard_shoplogo" src="{{ asset($shop->logo) }}" width=150 height=150 />
            <h1 class="headboard_shopname headboard_label">{{ $shop->shopname }}</h1>
        </div>
        <div class="headboard_info">
            <p class="headboard_username headboard_label"><b>Owner: </b>{{ $shop->username }}</p>
            <p class="headboard_nif headboard_label"><b>NIF/DNI: </b>{{ $shop->nif }}</p>
        </div>
    </section>
    <div class="admin-buttons">
        <a href="{{ route('shop.edit') }}"><button class="small-button">Edit your shop</button></a>
        <a href="{{ route('product.create') }}"><button class="small-button">Add product</button></a>
        <a href="{{ route('shop.show', $shop->url) }}"><button class="small-button">View as customer</button></a>
    </div>
    @if (session()->has('status'))
        <p>{{ session()->get('status') }}</p>
    @endif
    <h1 class="main_title">Products</h1>
    <div id="products" class="products">
        @forelse ($products as $key => $product)
            <div class="card-style-home product {{ $product->hidden ? 'hidden' : '' }}" id="{{ $product->id }}">
                <a class="card-style-home_a" href="{{ route('product.show', $product->id) }}">
                    <img class="product-img" src="{{ asset($product->image) }}" />
                    <h4 class="card-style-home_title product__name">{{ $product->name }}</h4>
                    <h4 class="card-style-home_title product__name">{{ $product->tag }}</h4>
                    <h3 class="card-style-home_title product__price">{{ $product->price }} €</h3>
                </a>
                <div class="shop_buttons_container">
                    <a href="{{ route('product.edit', ['id' => $product->id]) }}"><button class="shop_buttons"><img
                                class="icon" src="{{ asset('images/pen-to-square-solid.svg') }}"></button></a>
                    <form method="POST" action="{{ route('product.hide', ['id' => $product->id]) }}">
                        @csrf
                        <button type="submit" class="shop_buttons">
                            @if ($product->hidden)
                                <img class="icon" src="{{ asset('images/eye-solid.svg') }}">
                            @else
                                <img class="icon" src="{{ asset('images/eye-slash-solid.svg') }}">
                            @endif
                        </button>
                    </form>
                    <form method="POST" action="{{ route('product.destroy', ['id' => $product->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="shop_buttons"><img class="icon"
                                src="{{ asset('images/trash-solid.svg') }}"></button>
                    </form>
                </div>
            </div>
        @empty
            @if (session('request_search') != null)
                <h2>No results were found with '<?= session('request_search') ?>'.</h2>
            @else
                <h2>No results were found.</h2>
            @endif
        @endforelse
    </div>
    <div id="pagination">
        {{ $products->links('vendor.pagination.default') }}
    </div>
    <script type="module" src="{{ asset('js/pages/shop_show.js') }}"></script>
@endsection
