@extends('layouts.main')

@section('title', 'Shop not found')

@section('content')
  <h1>Shop not found</h1>
  <h4>This shop does not exist :(</h4>
  <a href="{{ route('product.index') }}">See other pages</a>
  <script type ="module" src="{{ asset('js/pages/product_index.js') }}"></script>
@endsection
