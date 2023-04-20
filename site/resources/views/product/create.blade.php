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
    <h1 class="main_title">Add product</h1>
    <form class="form" method='POST' action="{{ route('product.store') }}" enctype="multipart/form-data">
        @csrf
        <label class="form_label" for="product_name">Name:
        </label>
        <input type="text" class="form_input" placeholder="Chair" name="product_name"><br>
        <label class="form_label" for="product_name">Description:
        </label>
        <input type="text" class="form_input" placeholder="Very beautiful" name="product_description"><br>
        <label class="form_label" for="product_price">Price:
        </label>
        <input type="number" class="form_input" placeholder="12" name="product_price">€<br>
        <label class="form_label" for="product_image">Image(s):
        </label>
        <input type="file" class="form_input" name="product_image"><br>
        <label class="form_label" for="product_tag">Tag(s):
        </label>
        <input type="text" class="form_input" placeholder="furniture, wood" name="product_tag"><br>
        <label class="form_label" for="product_category">Category:
        </label>
        <select name="product_category" id="form__select_category">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ session('request_categories') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}</option>
            @endforeach
        </select><br>

        <button class="general-button btn-password" name="btn-password">Add product</button>
    </form>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
@endsection
