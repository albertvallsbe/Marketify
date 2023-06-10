    @extends('layouts.main')

    @section('title', 'Home')

    @section('content')
        <h1 class="main-home_title">Products</h1>
        <div id="products" class="products">
            @if ($products->count())
                @foreach ($products as $key => $product)
                    <div class="card-style-home product" id={{ $product->id }}>
                        <a class="card-style-home_a" href="{{ route('product.show', $product->id) }}">
                            <img class="product-img" src="{{ asset($paths[$product->id])}}"/>
                            <h4 class="card-style-home_title product__name">{{ $product->name }}</h4>
                            <h5 class="card-style-home_title product__description">{{ $product->description }}</h5>
                            <h3 class="card-style-home_title product__price">{{ $product->price }} €</h3>
                        </a>
                        @if ($shop == null)
                            <button class="btn-cart small-button" data-product-id="{{ $product->id }}">Add to cart</button>
                        @else
                            @if ($shop->id != $product->shop_id)
                                <button class="btn-cart small-button" data-product-id="{{ $product->id }}">Add to
                                    cart</button>
                            @else
                                <small class="card-style-home_property"><i>This product belongs to you.</i></small>
                            @endif
                        @endif
                    </div>
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
            {{ $products->links('vendor.pagination.default') }}
        </div>
        <script type="module" src="{{ asset('js/pages/product_index.js') }}"></script>
    @endsection
