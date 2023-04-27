    @extends('layouts.main')

    @section('title', 'Home')

    @section('content')
        <h1 class="main-home_title">Products</h1>
    <div id="products" class="products">
        @if ($products->count())
        @foreach ($products as $key => $product)
        @if (!$product->hidden)
            <div class="card-style-home product" id={{ $product->id }}>
            <a class="card-style-home_a" href="{{ route('product.show', $product->id) }}">
                {{-- </div>
                <div class="product__div_img"> --}}
                <img class="product-img" src="{{ asset($product->image) }}" />
                <h4 class="card-style-home_title product__name">{{ $product->name }}</h4>
                <h4 class="card-style-home_title product__name">{{ $product->tag }}</h4>
                <h3 class="card-style-home_title product__price">{{ $product->price }} €</h3>
            </a>
            @if($shop == null)
                <button class="btn-cart small-button" id="button-prueba" data-product-id="{{ $product->id }}">Add </button>
            @else
                @if($shop->id != $product->shop_id)
                    <button class="btn-cart small-button" id="button-prueba" data-product-id="{{ $product->id }}">Add </button>
                @endif
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
    <div id="pagination">
        {{-- {{ $products->appends(['search' => $search]) }} --}}
        {{ $products->links('vendor.pagination.default') }}
    </div>
    <script type ="module" src="{{ asset('js/pages/product_index.js') }}"></script>
@endsection
