@extends('layouts.main')

@section('title', 'Edit shop')

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
    <h1 class="main_title">Edit Shop: '{{$shop->shopname}}'</h1>
    <form class="form" method='POST' action="{{ route('shop.update') }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <label for="shopname">Store name</label><br><small><i>Changing your name will change your shop's URL!</i></small><br>
      <input type="text" name="shopname" id="shopname" value="{{$shop->shopname}}" placeholder="{{$shop->shopname}}" required><br>
      
      <label for="username">Name of user</label><br>
      <input type="text" name="username" id="username" value="{{$shop->username}}" placeholder="{{$shop->username}}" required><br>
      <label for="nif">NIF or DNI</label><br>
      <input type="text" name="nif" id="nif" value="{{$shop->nif}}" placeholder="{{$shop->nif}}" required><br>
      <label class="form_label" for="image">Logo:</label><br>
      <input type="file" name="image" id="image" accept="image/*"><br>
      <button type="submit">Apply changes</button>
  </form>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
@endsection
