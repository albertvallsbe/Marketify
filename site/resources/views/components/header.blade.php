<header>
    <div id="navbar">
        <a href="{{ route('product.index') }}">
            <img class="logo" src="{{ asset('images/marketify_logo.png') }}">
        </a>
        <form id="navbar__form" action="{{ route('product.index') }}" method="GET">
            <select name="filter" id="form__select_category">
                <option value="" {{ session('request_categories') == '' ? 'selected' : '' }}>All categories
                </option>

                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ session('request_categories') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}</option>
                @endforeach
            </select>
            <input type="text" placeholder="Search..." name="search" class="form__searchbar"
                value="{{ session('request_search') ? session('request_search') : '' }}"">
            <select name="order" id="form__select_orderby">
                @foreach ($options_order as $value => $label)
                    <option value="{{ $value }}" {{ $value == session('request_order') ? 'selected' : '' }}>
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
            <a href="{{ route('login.index') }}">
                <img class="icon icon_house" src="{{ asset('images/house-solid.svg') }}">
            </a>
            <div id="cart-icon">
                <a href="{{ route('cart.index') }}">
                    <img class="icon" src="{{ asset('images/cart-shopping-solid.svg') }}">
                    <span id="cart-count"></span>
                </a>
            </div>
        </div>
    </div>
</header>