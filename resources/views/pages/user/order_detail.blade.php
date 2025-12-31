@extends('layouts.template')

@section('content')

    <div>
        <h2>Detail Pesanan #{{ $order->order_id }}</h2>

        <p><strong>Total Harga:</strong> Rp {{ number_format($order->total_prices, 0, ',', '.') }}</p>
        <p><strong>Tanggal Pesanan:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</p>

        <h3>Items dalam Pesanan:</h3>
        @if ($order->orderItems->isEmpty())
            <p>Tidak ada item dalam pesanan ini.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Asal Produk</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->product->product_name }}</td>
                            <td>{{ optional($item->product->shop)->shop_name ?? 'Toko sudah tidak ada   ' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection
