@extends('layouts.template')

@section('title', 'Checkout | LelangGame')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h1 class="fw-bold">Detail Pesanan</h1>
                        <p class="text-secondary">
                            Order ID : {{ $order->order_id }} <br>
                            Status :
                            @if (ucfirst($order->status) == 'Unpaid')
                                <span class="badge bg-danger">{{ ucfirst($order->status) }}</span>
                            @else
                                <span class="badge bg-success">{{ ucfirst($order->status) }}</span>
                            @endif
                            <br>
                            Total Harga : Rp {{ number_format($order->total_prices, 0, ',', '.') }} <br>
                        </p>
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <div id="snap-container">
                    
                        </div>
                    </div>
                    {{-- @foreach ($order->orderItems as $item)
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
                    @endforeach --}}
                </div>
                </div>
                <div class="col-md-3"></div>
        </div>
    </div>
    <script type="text/javascript">
        window.snap.embed('{{ $snapToken }}', {
        embedId: 'snap-container'
    });
        </script>
    @endsection