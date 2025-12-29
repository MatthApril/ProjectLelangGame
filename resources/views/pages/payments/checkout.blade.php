<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <title>Document</title>
</head>

<body>

    <div>
        <h1>Detail Pesanan</h1>
        <p>Order ID: {{ $order->order_id }}</p>
        <p>Status: {{ ucfirst($order->status) }}</p>
        <p>Total Harga: Rp {{ number_format($order->total_prices, 0, ',', '.') }}</p>

        @foreach ($order->orderItems as $item)
            <div>
                <h3>{{ $item->product->product_name }}</h3>
                <img src="{{ asset('storage/' . $item->product->product_img) }}" alt="{{ $item->product->product_name }}"
                    width="200">
                <p>Toko: {{ $item->product->shop->shop_name }}</p>
                <p>Harga: Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                <p>Jumlah: {{ $item->quantity }}</p>
                <p>Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
            </div>
            <br>
        @endforeach

    </div>

    <div id="snap-container"></div>

    <script type="text/javascript">
        window.snap.embed('{{ $snapToken }}', {
            embedId: 'snap-container'
        });
    </script>

</body>

</html>
