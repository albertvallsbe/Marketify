<header>
    <div id="navbar">
        <div class="logo"></div>
        <form action="{{ route('home.index')}}" method="GET">
        <input type="text" placeholder="Search..." name="search" class="form__searchbar">
        <input type="submit" value="Q" id="form__buttonsearch">
        </form>
        <a class="navbar__login">Log in</a>
    </div>
</header>
