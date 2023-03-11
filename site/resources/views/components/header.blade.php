<header>
    <div id="navbar">
        <a href="{{ route('product.index') }}">
            <img class="logo" src="{{ asset('images/marketify_logo.png') }}">
        </a>
        <form id="navbar__form" action="{{ route('product.index') }}" method="GET">
            <input type="text" placeholder="Search..." name="search" class="form__searchbar">
            <select name="order">
                @foreach ($order_array as $value => $label)
                    <option value="{{ $value }}" {{ $value == $order ? 'selected' : '' }}>
                        {{ $label }}</option>
                @endforeach
            </select>
            <button id="form__buttonsearch" type="submit"><img class="icon"
                    src="{{ asset('images/magnifying-glass-solid.svg') }}"></button>
        </form>
        <div id="navbar__icons">
            
        <a href="{{ route('login.index') }}">
            <img class="icon icon_user" src="{{ asset('images/user-solid.svg') }}">
        </a>
        <a href="{{ route('cart.index') }}">
            <img class="icon" src="{{ asset('images/cart-shopping-solid.svg') }}">
        </a>
        </div>
    </div>
</header>
