@extends('layouts.main')

@section('title', 'Your messages')

@section('content')
    <h1 class="main_title">Your messages</h1>
    <div class="main_container">
        <div class="chat_container">
            @if ($chats->count())
                <ul class="chat_list">
                    @foreach ($chats as $key => $chat)
                        <li class="chat_item @if ($chat->notification->read == 0 && $chat->notification->user_id === auth()->id()) unread @endif"
                            data-chat-id="{{ $chat->id }}">
                            @if ($chat->seller_id != auth()->id())
                                <span class="chat_name">Seller: {{ $chat->seller->name }}</span>
                            @else
                                <span class="chat_name">Customer: {{ $chat->customer->name }}</span>
                            @endif                            
                        </li>
                        
                    @endforeach
                </ul>
                <section class="chat_open">
                    <ul class="message-list">
                        @foreach ($chats as $key => $chat)
                            <li class="message-item chat-{{ $chat->id }}">
                                @foreach ($chat->messages as $message)
                                    <div
                                        class="message-item_{{ $message->sender_id == auth()->id() ? 'receiver' : 'sender' }}">
                                        <div class="message-content">
                                            <p class="message-p">{{ $message->content }}
                                                <small class="message-time">
                                                    @if ($message->sender_id != auth()->id())
                                                        <strong>{{ $message->sender->name }}</strong>,
                                                    @endif
                                                    {{ $message->created_at->format('H:i') }}
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </li>
                            @php
                                $currentChatId = $chat->id;
                            @endphp
                        @endforeach
                    </ul>
                    <form id="chat-form" method="post" class="chat_open_form">
                        @csrf
                        <input type="text" name="messagetext" id="messagetext" placeholder="Write your message here...">
                        <button type="submit"><img src="" alt="">
                            <img class="icon" src="{{ asset('images/circle-arrow-right-solid.svg') }}"></button>
                    </form>
                </section>
            @else
                <h2>You don't have messages yet!</h2>
            @endif
        </div>
    </div>
    <script type="module" src="{{ asset('js/pages/messages_index.js') }}"></script>
@endsection
