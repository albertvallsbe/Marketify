@extends('layouts.main')

@section('title', 'Shop')

@section('content')
@if (session()->has('status'))
    <p>{{ session()->get('status') }}</p>
@endif
@if($errors->any())
    <div class="alert alert-error">
        @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
    <h1>Shop name: {{ $shop->shopname }}</h1>
    <img src="../{{ $shop->logo }}" width=150 height=150/>
    <p><b>NAME USER: </b>{{ $shop->username }}</p>
    <p><b>NIF/DNI: </b>{{ $shop->nif }}</p>
    
    <a href="{{ route('product.create') }}">Add product</a>

    <h1 class="main_title">Products</h1>
    <div id="products" class="products">
        @if ($products->count())
            @foreach ($products as $key => $product)
            @if ($product->hidden)
                <div class="card-style-mini product disabled" id={{ $product->id }}>
                    <a class="card-style-mini_a" href="{{ route('product.show', $product->id) }}">
                        <div class="product__div_img">
                            <img class="product__img" src="../{{ $product->image }}" />
                        </div>
                        <h4 class="card-style-mini_title product__name">{{ $product->name }}</h4>
                        <h4 class="card-style-mini_title product__name">{{ $product->tag }}</h4>
                        <h3 class="card-style-mini_title product__price">{{ $product->price }} €</h3>
                    </a>
                    <a href="{{ route('product.edit', ['id' => $product->id]) }}"><button>Edit</button></a>
                    <form method="POST" action="{{ route('product.destroy', ['id' => $product->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                    <form method="POST" action="{{ route('product.hide', ['id' => $product->id]) }}">
                        @csrf
                        <button type="submit">Show</button>
                    </form>
                    @else                <div class="card-style-mini product" id={{ $product->id }}>
                        <a class="card-style-mini_a" href="{{ route('product.show', $product->id) }}">
                            <div class="product__div_img">
                                <img class="product__img" src="../{{ $product->image }}" />
                            </div>
                            <h4 class="card-style-mini_title product__name">{{ $product->name }}</h4>
                            <h4 class="card-style-mini_title product__name">{{ $product->tag }}</h4>
                            <h3 class="card-style-mini_title product__price">{{ $product->price }} €</h3>
                        </a>
                        <a href="{{ route('product.edit', ['id' => $product->id]) }}"><button>Edit</button></a>
                        <form method="POST" action="{{ route('product.destroy', ['id' => $product->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    <form method="POST" action="{{ route('product.hide', ['id' => $product->id]) }}">
                        @csrf
                        <button type="submit">Hide</button>
                    </form>
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
        {{-- {{ $products->appends(['search' => $search]) }} --}}
        {{ $products->links('vendor.pagination.default') }}
    </div>
    {{-- <script type ="module" src="{{ asset('js/pages/shop_show.js') }}"></script> --}}
@endsection
