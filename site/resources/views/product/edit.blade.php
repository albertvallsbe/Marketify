@extends('layouts.main')

@section('title', 'Edit product')

@section('content')

<section class="main-shop">
  <section class="card-style-shop">
      <h1 class="card-style-shop_title shop-title">Modify your product</h1>
      <form class="form" method='POST' action="{{ route('product.update', ['id' => $product->id]) }}" enctype="multipart/form-data">
        @method('PUT')
          @csrf

          <label class="form_label" for="product_name">Name:</label>
          <input type="text" class="form_input @error('product_name') is-invalid @enderror" placeholder="{{$product->name}}" value="{{$product->name}}"
              name="product_name">
              @error('product_name')
                  <label class="form_label_invalid">{{ $message }}</label>
              @enderror

              <label class="form_label" for="product_description">Description:</label>
              <textarea class="form_input @error('product_description') is-invalid @enderror" name="product_description"
                  placeholder="{{$product->description}}">{{$product->description}}</textarea>
              @error('product_description')
                  <label class="form_label_invalid">{{ $message }}</label>
              @enderror

          <label class="form_label" for="product_price">Price:
          </label>
          <input type="number" class="form_input @error('product_price') is-invalid @enderror"
              name="product_price" placeholder="{{$product->price}}" value="{{$product->price}}">
              @error('product_price')
                  <label class="form_label_invalid">{{ $message }}</label>
              @enderror

          <label class="form_label" for="product_image">Image(s):
          </label>
          <input type="file" class="form_input @error('product_image') is-invalid @enderror" name="product_image">
          @error('product_image')
              <label class="form_label_invalid">{{ $message }}</label>
          @enderror


          <label class="form_label" for="product_tag">Tag(s): <small><i>(Add tags that describe your product, separated by commas)</i></small></label>
          <input type="text" class="form_input @error('product_tag') is-invalid @enderror"
              name="product_tag" placeholder="{{$product->tag}}" value="{{$product->tag}}">
              @error('product_tag')
                  <label class="form_label_invalid">{{ $message }}</label>
              @enderror

          <label class="form_label" for="product_category">Category:
          </label>
          <select name="product_category" class="form_input @error('product_category') is-invalid @enderror"
              id="form__select_category">
              @foreach ($categories as $category)
                  <option value="{{ $category->id }}"
                      {{ session('request_categories') == $category->id ? 'selected' : '' }}>
                      {{ $category->name }}</option>
              @endforeach
          </select>
          @error('product_category')
              <label class="form_label_invalid">{{ $message }}</label>
          @enderror
          <button class="general-button" type="submit">Create</button>
      </form>
      <p class="users-link">Modify your product!</p>
  </section>
</section>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
@endsection
