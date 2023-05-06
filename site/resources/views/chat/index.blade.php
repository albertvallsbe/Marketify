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
                            <br>

                            @if ($chat->messages->last()->sender_id == auth()->id())
                                <small><i>You:</i></small>
                            @endif
                            <small>{{ strlen($chat->messages()->orderBy('created_at', 'desc')->pluck('content')->last()) > 25? substr($chat->messages()->orderBy('created_at', 'desc')->pluck('content')->last(),0,20) . '...': $chat->messages()->orderBy('created_at', 'desc')->pluck('content')->last() }}
                                ·
                                {{ $chat->messages()->orderBy('created_at', 'desc')->pluck('created_at')->last()->format('H:i') }}
                            </small>
                        </li>
                    @endforeach
                </ul>
                <section class="chat_open">
                    <ul class="message-list">
                        @foreach ($chats as $key => $chat)
                        @php
                        $currentDate = null;
                        @endphp
                            @foreach ($chat->messages as $message)
                                <li class="message-item chat-{{ $chat->id }}">
                                    @php
                                    $messageDate = $message->created_at->format('Y-m-d');
                                @endphp
                                @if ($currentDate != $messageDate)
                                    <h1 class="message-item_date">
                                        @if($message->created_at->isToday())
                                            Today
                                        @elseif($message->created_at->isYesterday())
                                            Yesterday
                                        @else
                                            {{ $message->created_at->format('l, F jS, Y, H:i') }}
                                        @endif
                                    </h1>
                                    @php
                                        $currentDate = $messageDate;
                                    @endphp
                                @endif
                                    <div class="message-item_{{ $message->sender_id == auth()->id() ? 'receiver' : 'sender' }}">
                                        <div class="message-content">
                                            <p class="message-p">
                                                {{ $message->content }}
                                                @if ($message->automatic == true)
                                                <br>
                                                    <small><i>This message has been sent automatically.</i></small>
                                                @endif
                                                
                                                <small class="message-time">
                                                {{ $message->created_at->format('H:i') }}
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                            
                            <form method="post" action="{{ route('chat.confirmSeller', ['id' => $chat->id]) }}">
                                @csrf
                                <input type="hidden" name="actionValue" id="actionValue">
                                @if($chat->seller_id == auth()->id() && $chat->paymentDone == false)
                                    <button type="button" class="confirmButton" onclick="document.getElementById('actionValue').value = 'confirmPayment'; this.form.submit();">Confirm Payment</button>
                                @endif
                                @if($chat->seller_id == auth()->id() && $chat->shipmentSend == false && $chat->paymentDone == true)
                                    <button type="button" class="confirmButton" onclick="document.getElementById('actionValue').value = 'shipmentSend'; this.form.submit();">Confirm shipment</button>
                                @endif
                                
                                @if($chat->customer_id == auth()->id() && $chat->shipmentSend == true && $chat->shipmentDone == false)
                                    <button type="button" class="confirmButton" onclick="document.getElementById('actionValue').value = 'shipmentDone'; this.form.submit();">Order received</button>
                                @endif
                                <button type="submit" style="display: none;">Submit</button>
                            </form>
                            
                            <form id="chat-form" method="post" class="chat_open_form" action="{{ route('chat.messageSend', ['id' => $chat->id]) }}">
                                @csrf
                                <input type="text" name="messagetext" id="messagetext" placeholder="Write your message here..."
                                    autocomplete="off">
                                <button type="submit"><img class="icon" src="{{ asset('images/circle-arrow-right-solid.svg') }}"></button>
                            </form>
                        @endforeach
                    </ul>
                </section>
            @else
                <h2>You don't have messages yet!</h2>
            @endif
        </div>
    </div>

    <script type="module" src="{{ asset('js/pages/messages_index.js') }}"></script>
@endsection
