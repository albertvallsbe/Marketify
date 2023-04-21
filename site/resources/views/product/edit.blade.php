@extends('layouts.main')

@section('title', 'Add product')

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
    <h1 class="main_title">Edit product: '{{$product->name}}'</h1>
    <form class="form" method='POST' action="{{ route('product.update', ['id' => $product->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <label class="form_label" for="product_name">Name:
        </label>
        <input type="text" class="form_input" placeholder="{{$product->name}}" value="{{$product->name}}" name="product_name"><br>
        <label class="form_label" for="product_description">Description:
        </label>
        <input type="text" class="form_input" placeholder="{{$product->description}}" value="{{$product->description}}" name="product_description"><br>
        <label class="form_label" for="product_price">Price:
        </label>
        <input type="number" class="form_input" placeholder="{{$product->price}}" value="{{$product->price}}" name="product_price">€<br>
        <label class="form_label" for="product_image">Image(s):
        </label>
        <input type="file" class="form_input" name="product_image" value="{{$product->image}}"><br>
        <label class="form_label" for="product_tag">Tag(s):
        </label>
        <input type="text" class="form_input" placeholder="{{$product->tag}}" value="{{$product->tag}}" name="product_tag"><br>
        <label class="form_label" for="product_category">Category:
        </label>
        <select name="product_category" id="form__select_category">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ session('request_categories') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}</option>
            @endforeach
        </select><br>

        <button class="general-button btn-password" name="btn-password">Apply changes</button>
    </form>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
@endsection
