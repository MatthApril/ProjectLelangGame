@extends('layouts.template')

@section('title', 'Dashboard Seller | LelangGame')

@section('content')
    <div class="container my-4">
        <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-semibold">Status Toko
                            @if ($shop->status === 'open')
                                <span class="badge bg-success">Buka</span>
                            @else
                                <span class="badge bg-danger">Tutup</span>
                            @endif
                        </h5>
                        <small class="text-muted">Jam Operasional {{ $shop->open_hour }} - {{ $shop->close_hour }}</small>
                    </div>
                    <div>
                        <form action="{{ route('seller.shop.toggle-status') }}" method="POST">
                            @csrf
                            @if ($shop->status === 'open')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x-circle"></i> Tutup Toko
                                </button>
                            @else
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Buka Toko
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="fw-bold">Menu Cepat</h4>
        <div class="d-flex gap-2 mb-3">
            <a href="{{ route('seller.incoming_orders.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2"><i class="bi bi-clipboard"></i> Daftar Pesanan Pelanggan</a>
            <a href="{{ route('seller.products.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2"><i
                    class="bi bi-box-seam"></i> Kelola Produk</a>
            <a href="{{ route('seller.auctions.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2"><i class="bi bi-graph-up"></i> Kelola Lelang Produk</a>
            <a href="{{ route('seller.complaints.index') }}"
                    class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2">
                    <i class="bi bi-chat"></i> Kelola Keluhan</a>
            <a href="{{ route('seller.reviews.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2"><i class="bi bi-star"></i> Lihat Ulasan</a>
        </div>
        <hr>

        <div>
            <h6 class="fw-bold">Keuangan Toko</h6>
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

            @if ($shop->shop_img)
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
            @endif
        </div>
    @endsection
