@extends('layouts.main')

@section('title', 'Your messages')

@section('content')
    <h1 class="main_title">Your messages</h1>
    <div class="main_container">
        <div class="chat_container">
            @if ($chats->count())
                <ul class="chat_list">
                    @foreach ($chats as $key => $chat)
                        <a href="{{ route('chat.show', ['id' => $chat->id]) }}" class="link_show">
                            <li class="chat_item @if ($chat->notification->read == 0 && $chat->notification->user_id === auth()->id()) unread @endif"
                                data-chat-id="{{ $chat->id }}">
                                @if ($chat->seller_id != auth()->id())
                                    <span class="chat_name">Buying Order: #{{ $chat->order->id }}</span>
                                @else
                                    <span class="chat_name">Selling Order: #{{ $chat->order->id }}</span>
                                @endif
                                <br>

                                @if ($chat->messages->last()->sender_id == auth()->id())
                                    <small><i>You:</i></small>
                                @endif
                                <small>{{ strlen($chat->messages()->orderBy('created_at', 'desc')->pluck('content')->last()) > 25? substr($chat->messages()->orderBy('created_at', 'desc')->pluck('content')->last(),0,20) . '...': $chat->messages()->orderBy('created_at', 'desc')->pluck('content')->last() }}
                                    ·
                                    {{ $chat->messages()->orderBy('created_at', 'desc')->pluck('created_at')->last()->format('H:i') }}
                                </small>
                            </li>
                        </a>
                    @endforeach
                </ul>
                <section class="chat_open">
                    @if (!$selectedChat)
                        <h3>Open a chat!</h3>
                    @endif
                </section>
            @else
                <h2>You don't have messages yet!</h2>
            @endif
        </div>
    </div>

    <script type="module" src="{{ asset('js/pages/messages_index.js') }}"></script>
@endsection
