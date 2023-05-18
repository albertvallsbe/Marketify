@extends('layouts.main')

@section('title', $product->name)

@section('content')
    <section class="main-productshow">
        <section class="card-style-productshow"=>
            <img class="card-style-productshow_image" src="{{ asset($product->image) }}" height="auto" width="500px">
        </section>
        <section class="card-style-productshow-details">
            <h1 class="card-style-productshow-details_title">{{ $product->name }}</h1>
            <p class="card-style-productshow-details_content"><b>Description:</b> {{ $product->description }}</p>
            <p class="card-style-productshow-details_content"><b>Category:</b> {{ $categoryname }}</p>
            <p class="card-style-productshow-details_content"><b>Shop:</b> {{ $shopname }}</p>
            <p class="card-style-productshow-details_content"><b>Price:</b> {{ $product->price }} €</p>
            @if ($product->status == 'active')
                @if ($shop == null)
                    <button class="btn-cart small-button" data-product-id="{{ $product->id }}">Add to cart</button>
                @else
                    @if ($shop->id != $product->shop_id)
                        <button class="btn-cart small-button" data-product-id="{{ $product->id }}">Add to cart</button>
                    @else
                        <small class="card-style-home_property"><i>This product belongs to you.</i></small>
                    @endif
                @endif
            @else
                <small class="card-style-home_property"><i>This product has already sold.</i></small>
            @endif
        </section>
    </section>
    <script type="module" src="{{ asset('js/pages/product_show.js') }}"></script>
@endsection
