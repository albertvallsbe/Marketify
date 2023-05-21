@extends('layouts.main')

@section('title', 'Historical')

@section('content')
    <div class="historical-container">
        <div class="title-container">
            <h1 class="historical-title">Historical Orders</h1>
        </div>
        @foreach ($orders as $order)
            <a href="{{ route('historical.details', $order->id) }}">
                <div class="container-order" id={{ $order->id }}>
                    <h3>{{ strtoupper('Order : ') }} {{ $order->id }}</h1>
                        <div class="date-container">
                            <p>{{ strtoupper('Date : ') }}{{ $order->created_at }}</p>
                        </div>
                </div>
            </a>
        @endforeach
        <div id="pagination">
            {{ $orders->links('vendor.pagination.default') }}
        </div>
    </div>
    <script type="module" src="{{ asset('js/app.js') }}"></script>
@endsection
