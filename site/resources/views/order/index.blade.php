@extends('layouts.main')

@section('title', 'Orders')

@section('content')
    <h1 class="main_title">Orders</h1>
    @if ($productsByShop)
        <section class="main-orders">
            <section class="orders-card">
                <div class="product card-style-orders">

                    @foreach ($productsByShop as $key => $shopByProduct)
                        <div class="card-seller">
                            <h2>{{ $shopName[$key] }}</h2>
                            @foreach ($shopByProduct as $productOrder)
                                <div class="card-items-list">
                                    <div class="align-text">
                                        {{ $productOrder->name }},
                                    </div>
                                    <div>
                                        {{ $productOrder->price }}€
                                    </div>
                                </div>
                                @php
                                    if ($productOrder->status != 'active') {
                                        $productError = true;
                                    } else {
                                        $productError = false;
                                    }
                                @endphp
                            @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
                <div class="btns-cart">
                    <form method="POST" action="{{ route('order.add') }}">
                        @csrf
                        <button
                            class="btn-order general-button general-button_order @if ($productError) btn-disabled @endif"
                            @if ($productError) disabled @endif type="submit">COMPLETE PURCASHE
                        </button>
                    </form>

                    @if ($productError)
                        <p>Some products have been sold. Please <a href="javascript:void(0);"
                                onclick="history.back();">return to
                                cart</a> to delete them.</p>
                    @endif
                </div>
                @if (!auth()->user())
                    <i><a href="/login">Log in</a> to buy in our website.</i>
                @endif
            </section>
        </section>
    @else
        <p>Error loading your order.</p>
    @endif
    <script type="module" src="{{ asset('js/pages/order_index.js') }}"></script>
@endsection
