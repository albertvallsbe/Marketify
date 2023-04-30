@extends('layouts.main')

@section('title', 'Edit shop')

@section('content')
    <section class="main-shop">
        <section class="card-style-shop">
            <h1 class="card-style-shop_title shop-title">Edit Shop: '{{ $shop->shopname }}'</h1>
            <form class="form" method='POST' action="{{ route('shop.update') }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <label class="form_label" for="shopname">Store name <small><i>(Changing your name will change your shop's
                            URL!):</i></small></label>
                <input type="text" class="form_input input-mail @error('shopname') is-invalid @enderror" name="shopname"
                    id="shopname" value="{{ $shop->shopname }}" placeholder="{{ $shop->shopname }}">
                @error('shopname')
                    <label class="form_label_invalid">{{ $message }}</label>
                @enderror

                <label class="form_label" for="username">Your full name:</label>
                <input type="text" class="form_input input-mail @error('username') is-invalid @enderror" name="username"
                    id="username" value="{{ $shop->username }}" placeholder="{{ $shop->username }}">
                @error('username')
                    <label class="form_label_invalid">{{ $message }}</label>
                @enderror

                <label class="form_label" for="nif">Your DNI/NIF:</label>
                <input type="text" class="form_input input-mail @error('nif') is-invalid @enderror" name="nif"
                    id="nif" value="{{ $shop->nif }}" placeholder="{{ $shop->nif }}">
                @error('nif')
                    <label class="form_label_invalid">{{ $message }}</label>
                @enderror

                <label class="form_label" for="image">Shop's logo:</label>
                <input type="file" class="form_input input-mail @error('image') is-invalid @enderror"
                    placeholder="example@gmail.com" name="image" id="image" accept="image/*">
                @error('image')
                    <label class="form_label_invalid">{{ $message }}</label>
                @enderror

                <label class="form_label" for="header_color">Shop's color:</label>
                <input type="color" class="form_input_color @error('image') is-invalid @enderror"
                    value="{{ $shop->header_color }}" name="header_color" id="header_color">
                @error('header_color')
                    <label class="form_label_invalid">{{ $message }}</label>
                @enderror

                <button class="general-button" type="submit">Apply changes</button>
            </form>
            <p class="users-link">Customize your shop!</p>
        </section>
    </section>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
@endsection
