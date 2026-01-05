@extends('layouts.template')

@section('title', 'Transaksi | LelangGame')

@section('content')
    <div class="container">
        <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb mt-3">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
                <li class="breadcrumb-item active" aria-current="page">Transaksi</li>
            </ol>
        </nav>
        <h2 class="fw-semibold">Transaksi</h2>
        <a href="{{ route('user.complaints.index') }}">List Complaint</a>
        <hr>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h6 class="mb-5">Border Hijau : <span class="text-success fw-semibold">Sudah Bayar</span> | Border Merah : <span
                class="text-danger fw-semibold">Belum Bayar</span> | Border Abu-abu : <span
                class="text-secondary fw-semibold">Kadaluarsa</span></h6>
        @if ($orders->isEmpty())
            <div class="text-center">
                <div>
                    <img src="{{ asset('images/order-empty.png') }}" alt="Order Empty" width="300">
                </div>
                <div>
                    <h5 class="fw-semibold">Anda belum memiliki transaksi.</h5>
                    <p>Belum ada transaksi, silahkan lakukan pemesanan terlebih dahulu.</p>
                </div>
            </div>
        @else
            @foreach ($orders as $order)
                @if ($order->status == 'unpaid' && !now()->lessThan($order->expire_payment_at))
                    <div class="card p-3 mb-3 border-secondary border-3">
                    @else
                        @if (ucfirst($order->status) == 'Unpaid')
                            <div class="card p-3 mb-3 border-danger border-3">
                            @else
                                <div class="card p-3 mb-3 border-success border-3">
                        @endif
                @endif
                <h6 class="fw-semibold">ID Transaksi : #{{ $order->order_id }}</h6>
                <span>Tanggal Transaksi : {{ $order->created_at->format('d-m-Y H:i') }}</span>
                <hr>
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="fw-semibold m-0">Total Harga : Rp {{ number_format($order->total_prices, 0, ',', '.') }}</h6>
                    <div class="text-end">
                        <a href="{{ route('user.orders.detail', ['orderId' => $order->order_id]) }}"
                            class="btn btn-outline-primary btn-sm my-2"><i class="bi bi-eye"></i> Detail</a>
                        @if ($order->status == 'unpaid' && now()->lessThan($order->expire_payment_at))
                            <form action="{{ route('payment.show.payment') }}" method="post" class="d-inline my-2">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                                <button type="submit" class="btn btn-outline-success btn-sm"><i
                                        class="bi bi-cash-coin"></i> Pembayaran</button>
                            </form>
                        @endif
                    </div>
                </div>
    </div>
    @endforeach
    {{-- <div class="table-wrapper">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Tanggal Pesanan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                            <td>Rp {{ number_format($order->total_prices, 0, ',', '.') }}</td>
                            <td>
                                @if ($order->status == 'unpaid' && !now()->lessThan($order->expire_payment_at))
                                    <span class="badge bg-secondary">Expired</span>
                                @else
                                    @if (ucfirst($order->status) == 'Unpaid')
                                        <span class="badge bg-danger">{{ ucfirst($order->status) }}</span>
                                    @else
                                        <span class="badge bg-success">{{ ucfirst($order->status) }}</span>
                                    @endif
                                @endif
                            </td>
                            <td class="d-flex align-items-center gap-2">
                                <a href="{{ route('user.orders.detail', ['orderId' => $order->order_id]) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i> Detail</a>
                                @if ($order->status == 'unpaid' && now()->lessThan($order->expire_payment_at))
                                    <form action="{{ route('payment.show.payment') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                                        <button type="submit" class="btn btn-outline-success btn-sm"><i class="bi bi-cash-coin"></i> Pembayaran</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div> --}}
    @endif
    </div>

@endsection
