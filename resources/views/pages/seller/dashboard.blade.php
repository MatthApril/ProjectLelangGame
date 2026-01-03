@extends('layouts.template')

@section('title', 'Dashboard Seller | LelangGame')

@section('content')
    <div class="container mt-3">
        <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb mt-3">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard Seller</li>
            </ol>
        </nav>
        <h2 class="fw-bold">Dashboard Seller - {{ $shop->shop_name }}</h2>
        <hr>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h4 class="fw-bold">Menu Cepat</h4>
        <div class="d-flex gap-2 mb-3">
            <a href="{{ route('seller.products.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2"><i
                    class="bi bi-box-seam"></i> Kelola Produk</a>
            <a href="{{ route('seller.auctions.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2"><i class="bi bi-graph-up"></i> Daftar Lelang</a>
            <a href="{{ route('seller.incoming_orders.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2"><i class="bi bi-clipboard2"></i> Daftar Pesanan Masuk</a>
        </div>
        <hr>

        <br>

        <h6 class="fw-bold">Keuangan Toko</h2>
            <table border="1" class="table table-striped">
                <tr>
                    <td><strong>Saldo Toko</strong></td>
                    <td><strong>Transaksi Berjalan</strong></td>
                </tr>
                <tr>
                    <td>Rp {{ number_format($shopBalance, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($runningTransactions, 0, ',', '.') }}</td>
                </tr>
            </table>

            <br>

            <h6 class="fw-bold">Statistik Produk</h6>
            <table border="1" class="table table-striped">
                <tr>
                    <td><strong>Total Produk</strong></td>
                    <td>{{ $totalProducts }}</td>
                </tr>
                <tr>
                    <td><strong>Produk Tersedia</strong></td>
                    <td>{{ $activeProducts }}</td>
                </tr>
                <tr>
                    <td><strong>Total Pesanan</strong></td>
                    <td>{{ $totalOrders }}</td>
                </tr>
            </table>

            <br>

            <h6 class="fw-bold">Informasi Toko</h6>
            <table border="1" class="table table-striped">
                <tr>
                    <td><strong>Status Toko</strong></td>
                    <td>{{ ucfirst($shop->status) }}</td>
                </tr>
                <tr>
                    <td><strong>Rating</strong></td>
                    <td>{{ number_format($shop->shop_rating, 1) }} / 5.0</td>
                </tr>
                <tr>
                    <td><strong>Jam Operasional</strong></td>
                    <td>{{ $shop->open_hour }} - {{ $shop->close_hour }}</td>
                </tr>
            </table>
    </div>
@endsection
