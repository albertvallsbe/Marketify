@extends('layouts.main')

@section('title', 'Edit your profile')

@section('content')
    <h1>Management page</h1>
    @if (session()->has('status'))
        <p class="session_success">{{ session()->get('status') }}</p>
    @endif
    <section class="main-management">
        <section class="card-style-historical">
            <h3 class="card-style-historical_title">Historic orders</h3>
            <p class="card-style-chats_text"> Check your historical orders list! </p>
            <div class="btns-management">
                <a href="{{ route('historical.index') }}">
                    <button class="general-button btn-historical" name="btn-password">ORDERS</button>
                </a>
            </div>
        </section>
        <section class="card-style-chats">
            <h3 class="card-style-chats_title">Your chats</h3>
            <p class="card-style-chats_text"> Check your chats! </p>
            <div class="btns-management">
                <a href="{{ route('chat.index') }}">
                    <button class="general-button btn-chats" name="btn-avatar-rm">CHATS</button>
                </a>
            </div>
        </section>
    @endsection
