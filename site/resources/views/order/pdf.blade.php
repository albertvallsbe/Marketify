<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order {{ $order->id }}.pdf</title>
</head>

<body>
    <div class="ticket-container">
        <div class="product-info">
            <div class="title-ticket">
                <h2 class="ticket-title">ORDER TICKET</h2>
            </div>
            <div class="title-ticket">
                @foreach ($shop as $item)
                    <h3>{{ $item->shopname }}</h2>
                @endforeach
            </div>
            @foreach ($products as $orderItem)
                <div class="order-products">
                    <div class="products-tickets">
                        <h4>{{ strtoupper($orderItem->name) }}</h4>
                        <p class='price-product'>{{ $orderItem->price }} €</p>
                    </div>
                </div>
            @endforeach
            <div class="total-container">
                <h3 class="total-price"> </h3>
            </div>
        </div>
</body>
<script type="module" src="{{ asset('js/pages/historical.js') }}"></script>

</html>
