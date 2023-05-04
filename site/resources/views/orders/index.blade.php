@extends('layouts.main')

@section('title', 'Orders')

@section('content')
    <h1 class="main_title">Orders</h1>
    <section class="main-orders">
        <section class="orders-card">
            {{-- @if (count($product) != 0) --}}
                @foreach ($shops as $shop)
                    <div class="product card-style-orders">
                        <a class="card-style-orders_a" href="{{ route('product.filter', $shop['name']->id) }}">
                            <h2 class="products-title">{{ $shop['name']->name }}</h2>
                        </a>
                    </div>
                @endforeach
            {{-- @else
                <h2>No results were found.</h2>
            @endif --}}
        </section>
    </section>
    <script type="module" src="{{ asset('js/pages/orders_index.js') }}"></script>
@endsection
