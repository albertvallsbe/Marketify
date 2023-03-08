<header>
    <div id="navbar">
        <a href="{{ route('home.index') }}">
            <img class="logo" src="{{ asset('images/marketify_logo.png') }}">
        </a>
        <form id="navbar__form" action="{{ route('home.index') }}" method="GET">
            <input type="text" placeholder="Search..." name="search" class="form__searchbar">
            <button id="form__buttonsearch" type="submit"><img class="icon"
                    src="{{ asset('images/magnifying-glass-solid.svg') }}"></button>
        </form>
        <div id="navbar__icons">
            
        <a href="{{ route('home.login') }}">
            <img class="icon icon_user" src="{{ asset('images/user-solid.svg') }}">
        </a>
            <img class="icon" src="{{ asset('images/cart-shopping-solid.svg') }}">
        </div>
    </div>
</header>
