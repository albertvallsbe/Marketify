@extends('layouts.main')

@section('title', $product->name)

@section('content')
    <section class="main-productshow">
        <section class="card-style-productshow">
            <div class="slider">
                @for ($i = 0; $i < count($paths); $i++)
                    <div class="slide">
                        <img id="slide{{ $i }}" src="{{ asset($paths[$i]['path']) }}"
                            alt="Slide {{ $i + 1 }}">
                    </div>
                @endfor
            </div>

            <div class="slider-buttons">
                @for ($i = 0; $i < count($paths); $i++)
                    <button class="slide-button{{ $i === 0 ? ' active' : '' }}"
                        data-slide-index="{{ $i }}">{{ $i + 1 }}</button>
                @endfor
            </div>
        </section>
        <section class="card-style-productshow-details">
            <h1 class="card-style-productshow-details_title">{{ $product->name }}</h1>
            <p class="card-style-productshow-details_content"><b>Description:</b> {{ $product->description }}</p>
            <p class="card-style-productshow-details_content"><b>Category:</b> {{ $categoryname }}</p>
            <p class="card-style-productshow-details_content"><b>Shop:</b><a
                    href="{{ route('shop.show', $productShop->url) }}" target="_blank"> {{ $productShop->shopname }}</a>
            </p>
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
