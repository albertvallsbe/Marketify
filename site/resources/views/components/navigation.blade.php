<div id="navigation" class="navigation">
    {{-- <a href="{{ route('product.index') }}">
    <img class="icon" src="{{ asset('images/house-solid.svg') }}">
  </a> --}}
    @can('seller')
        <a href="{{ route('shop.admin') }}"><img class="icon" src="{{ asset('images/shop-solid.svg') }}"></a>
    @elsecan('customer')
        <a href="{{ route('shop.index') }}"><img class="icon" src="{{ asset('images/shop-solid.svg') }}"></a>
    @else
        <a href="{{ route('landing.index') }}"><img class="icon" src="{{ asset('images/house-solid.svg') }}"></a>
    @endcan



    @if (auth()->check())
            <a href="{{ route('user.edit') }}">
                <img class="icon icon_user" src="{{ asset(auth()->user()->avatar) }}">
            </a>
    @else
        <a href="{{ route('login.index') }}">
            <img class="icon" src="{{ asset('images/user-solid.svg') }}">
    @endif
</div>
