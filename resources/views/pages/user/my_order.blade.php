@extends('layouts.template')

@section('content')

    <div>
        <h2>Riwayat Pesanan Saya</h2>

        @if ($orders->isEmpty())
            <p>Anda belum memiliki pesanan.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total Harga</th>
                        <th>Tanggal Pesanan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>Rp {{ number_format($order->total_prices, 0, ',', '.') }}</td>
                            <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                            <td>
                                <a href="{{ route('user.orders.detail', ['orderId' => $order->order_id]) }}">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection
