@extends('layouts.main')

@section('title', 'Product not found')

@section('content')
  <h1>Product not found</h1>
  <h4>This product does not exist :(</h4>
  <a href="{{ route('product.index') }}">See more products</a>
  <script type ="module" src="{{ asset('js/pages/product_index.js') }}"></script>
@endsection
