<header>
    <div id="navbar" class="navbar">
        <a href="{{ route('landing.index') }}">
            <img class="logo" src="{{ asset('images/marketify_logo.png') }}">
        </a>
        <form class="navbar__form" id="navbar__form" action="{{ route('product.index') }}" method="GET">
            <select class="navbar__form--select-category" name="filter" id="form__select_category">
                <option value="" {{ session('request_categories') == '' ? 'selected' : '' }}>All categories
                </option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ session('request_categories') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}</option>
                @endforeach
            </select>

            <input class="navbar__form--search-bar" type="text" placeholder="Search..." name="search"
                value="{{ session('request_search') ? session('request_search') : '' }}"">

            <select class="navbar__form--select-orderby" name="order" id="form__select_orderby">
                @foreach ($options_order as $value => $label)
                    <option value="{{ $value }}" {{ $value == session('request_order') ? 'selected' : '' }}>
                        {{ $label }}</option>
                @endforeach
            </select>

            <button class="navbar__form--button-search" id="form__buttonsearch" type="submit">
                <img class="icon" src="{{ asset('images/magnifying-glass-solid.svg') }}">
            </button>
        </form>
        <div id="navbar__icons" class="navbar__icons">

            @if (auth()->check())
                <div class="menu-container">
                    <a href="{{ route('user.edit') }}">
                        <img class="icon icon_user" src="{{ asset(auth()->user()->avatar) }}">
                    </a>
                    <div class="menu-toggle">
                        <button class="toggle-button">v</button>
                    </div>
                    <ul class="menu-list">
                        @can('seller')
                            <li><a href="{{ route('shop.admin') }}">Your shop</a></li>
                        @elsecan('customer')
                            <li><a href="{{ route('shop.index') }}">Create shop</a></li>
                        @endcan
                        <li><a href="{{ route('user.logout') }}" class="logout">Log Out</a></li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login.index') }}">
                    <img class="icon" src="{{ asset('images/user-solid.svg') }}">
            @endif

            <div id="cart-icon" class="cart-icon">
                <a id="cart-link" href="{{ route('cart.index') }}">
                    <img class="icon" src="{{ asset('images/cart-shopping-solid.svg') }}">
                    <span id="cart-count" class="cart-count"></span>
                </a>
            </div>
        </div>
    </div>
</header>

<script type="module" src="{{ asset('js/components/header.js') }}"></script>
