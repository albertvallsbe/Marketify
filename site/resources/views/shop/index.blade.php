@extends('layouts.main')

@section('title', 'Shop creator')

@section('content')

<section class="main-shop">
    <section class="card-style-shop">
        <h1 class="card-style-shop_title shop-title">Create your shop</h1>
    @can('seller')
        <p>You already have a shop!</p>
        <a href="../">Go back</a>
    @elsecan('shopper')
        <form class="form" method="POST" action="{{ route('shop.create') }}" enctype="multipart/form-data">
            @csrf
            
            <label class="form_label"  for="shopname">Store name</label>
            <input type="text" class="form_input input-mail @error('shopname') is-invalid @enderror"
                placeholder="My shop" name="shopname" id="shopname" value="{{ old('shopname') }}">
            @error('shopname')
                <label class="form_label_invalid">{{ $message }}</label>
            @enderror
            
            <label class="form_label"  for="username">Your full name</label>
            <input type="text" class="form_input input-mail @error('username') is-invalid @enderror"
                placeholder="Pere Pou" name="username" id="username" value="{{ old('username') }}">
            @error('username')
                <label class="form_label_invalid">{{ $message }}</label>
            @enderror

            <label class="form_label"  for="nif">Your NIF</label>
            <input type="text" class="form_input input-mail @error('nif') is-invalid @enderror"
                placeholder="XXXXXXXXA" name="nif" id="nif" value="{{ old('nif') }}">
            @error('nif')
                <label class="form_label_invalid">{{ $message }}</label>
            @enderror            

            <label class="form_label"  for="image">Shop's logo:</label>
            <input type="file" class="form_input input-mail @error('image') is-invalid @enderror"
                placeholder="example@gmail.com" name="image" id="image" accept="image/*">
            @error('image')
                <label class="form_label_invalid">{{ $message }}</label>
            @enderror            
            
            <button class="general-button" type="submit">Submit</button>
        </form>
        <p class="users-link">Create your shop to sell your products!</p>
    @endcan
    </section>
</section>
    {{-- <script type="module" src="{{ asset('js/pages/shop_creator.js') }}"></script> --}}
@endsection
