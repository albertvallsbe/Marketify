<div id="navigation" class="navigation">
    @can('seller')
        <a href="{{ route('shop.admin') }}"><img class="icon" src="{{ asset('images/icons/shop-solid.svg') }}"></a>
    @elsecan('customer')
        <a href="{{ route('shop.index') }}"><img class="icon" src="{{ asset('images/icons/shop-solid.svg') }}"></a>
    @else
        <a href="{{ route('landing.index') }}"><img class="icon" src="{{ asset('images/icons/house-solid.svg') }}"></a>
    @endcan



    @if (auth()->check())
        <a href="{{ route('user.edit') }}">
            <img class="icon icon_user" src="{{ asset(auth()->user()->avatar) }}">
        </a>
    @else
        <a href="{{ route('login.index') }}">
            <img class="icon" src="{{ asset('images/icons/user-solid.svg') }}">
    @endif

    <div id="chat-icon" class="chat-icon">
        <a href="{{ route('chat.index') }}">
            <img class="icon" src="{{ asset('images/icons/envelope-solid.svg') }}">
            @if (session('notificationCount') != 0)
                <span id="notification-count" class="notification-count">
                    {{ session('notificationCount') }}
                </span>
            @endif
        </a>
    </div>
</div>
