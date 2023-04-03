@extends('layouts.main')

@section('title', 'Cart')

@section('content')
  <h1 class="main_title">Cart</h1>
  <section class="main-container">
    <section class="products-cart">
      @if (count($products) != 0)
        @foreach ($products as $key => $product)
          <div class="product card-style-mini" id={{ $product->id }}>
            <a class="card-style-mini_a" href="{{ route('product.show', $product->id) }}">
              <div class="product__div_img">
                <img class="product__img" src="{{ $product->image }}" />
              </div>
              <h4 class="card-style-mini_title product__name">{{ $product->name }}</h4>
              <h3 class="card-style-mini_title product__price">{{ $product->price }} €</h3>
            </a>
            <button class="btn-remove small-button" data-product-id="{{ $product->id }}">X</button>
          </div>
        @endforeach
      @else
        <h2>No results were found.</h2>
      @endif
    </section>
    <section class="card-style-cart section summary">
      <h1 class="card-style-cart_title title">Summary</h1>
      <div class="users-link products-cartt">

        <div class="individual_prices"></div>
        <h3 class="total">Total: €</h3>
      </div>
      <div class="btns-cart">
        <button class="btn-empty general-button">EMPTY CART</button>
        <button class="btn-buy general-button general-button_buy">BUY ALL</button>
      </div>
    </section>
  </section>
    <script type="module" src="{{ asset('js/pages/cart_index.js') }}"></script>
@endsection
