@extends('layouts.main')

@section('title', 'Your messages')

@section('content')
<h1 class="main_title">Your messages</h1>

    <div class="chat_container">
        @if ($chats->count())
            <ul class="chat_list">
                @foreach ($chats as $key => $chat)
                    <li class="chat_item" data-chat-id="{{ $chat->id }}">
                        @if ($chat->seller_id != auth()->id())
                            <span class="chat_name">Seller: {{ $chat->seller->name }}</span>
                        @else
                            <span class="chat_name">Customer: {{ $chat->customer->name }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
            <section class="chat_open">
                <h3>OPEN CHAT</h3>
                <ul class="message-list">
                    @foreach ($chats as $key => $chat)
                        <li class="message-item chat-{{ $chat->id }}">
                            @foreach ($chat->messages as $message)
                                @if ($message->sender_id != auth()->id())
                                    <div style="text-align: left">
                                        <small><strong>{{ $message->sender->name }}</strong>, {{ $message->created_at }}</small>
                                        <p>{{ $message->content }}</p>
                                @else
                                    <div style="text-align: right">
                                        <small><strong>{{ $message->sender->name }}</strong>, {{ $message->created_at }}</small>
                                        <p style="text-align: right">{{ $message->content }}</p>
                                @endif
                            </div>
                            @endforeach
                        </li>
                    @endforeach
                </ul>
            </section>
        @else
            <h2>You don't have messages yet!</h2>
        @endif
    </div>

    <script type="module" src="{{ asset('js/pages/messages_index.js') }}"></script>
@endsection
