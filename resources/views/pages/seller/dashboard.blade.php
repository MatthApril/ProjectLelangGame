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
        
        <h4 class="fw-semibold">Menu Seller</h4>
        <div class="d-flex gap-2 mb-2 flex-wrap">
            <a href="{{ route('seller.incoming_orders.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2">
                <i class="bi bi-clipboard"></i> Pesanan Pelanggan
            </a>
            <a href="{{ route('seller.products.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2">
                <i class="bi bi-box-seam"></i> Kelola Produk
            </a>
            <a href="{{ route('seller.auctions.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2">
                <i class="bi bi-graph-up"></i> Lelang Produk
            </a>
            <a href="{{ route('seller.complaints.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2">
                <i class="bi bi-chat"></i> Komplain Pelanggan
            </a>
            <a href="{{ route('seller.reviews.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2">
                <i class="bi bi-star"></i> Lihat Ulasan
            </a>
            <a href="{{ route('seller.transaction-report.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2">
                <i class="bi bi-file-earmark-text"></i> Laporan Transaksi
            </a>
        </div>
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

        {{-- Informasi Toko --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        @if($shop->shop_img)
                            <img src="{{ asset('storage/' . $shop->shop_img) }}" alt="{{ $shop->shop_name }}" 
                                 class="img-fluid rounded" style="max-width: 150px;">
                        @else
                            <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" 
                                 style="width: 150px; height: 150px; margin: 0 auto;">
                                <i class="bi bi-shop" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h4 class="fw-bold">{{ $shop->shop_name }}</h4>
                        <p class="mb-1">
                            <i class="bi bi-clock"></i> 
                            Jam Operasional : {{ $shop->open_hour }} - {{ $shop->close_hour }}
                        </p>
                        <p class="mb-1">
                            <i class="bi bi-star-fill text-warning"></i> 
                            Rating : {{ number_format($averageRating ?? 0, 1) }} / 5.0
                        </p>
                        <p class="mb-1">
                            Status Toko : 
                            @if($shop->status === 'open')
                                <span class="text-success fw-semibold">Buka</span>
                            @else
                                <span class="text-danger fw-semibold">Tutup</span>
                            @endif
                        </p>
                        <form action="{{ route('seller.shop.toggle-status') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $shop->status === 'open' ? 'btn-danger' : 'btn-success' }}">
                                <i class="bi bi-{{ $shop->status === 'open' ? 'lock' : 'unlock' }}"></i>
                                {{ $shop->status === 'open' ? 'Tutup Toko' : 'Buka Toko' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik Keuangan --}}
        <h5 class="fw-bold mb-3">Keuangan Toko</h5>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card card-left-success">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Saldo Tersedia</h6>
                        <h3 class="fw-bold text-success">Rp {{ number_format($shopBalance, 0, ',', '.') }}</h3>
                        <small class="text-muted">Dapat dicairkan</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-left-warning">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Saldo Dalam Proses</h6>
                        <h3 class="fw-bold text-warning">Rp {{ number_format($runningTransactions, 0, ',', '.') }}</h3>
                        <small class="text-muted">Menunggu penyelesaian</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-left-primary">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Pendapatan</h6>
                        <h3 class="fw-bold text-primary">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        <small class="text-muted">Transaksi selesai</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik Produk --}}
        <h5 class="fw-bold mb-3">Statistik Produk</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Kategori</th>
                        <th class="text-center">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Produk</td>
                        <td class="text-center fw-bold">{{ $totalProducts }}</td>
                    </tr>
                    <tr>
                        <td>Produk Aktif (Stok > 0)</td>
                        <td class="text-center text-success fw-bold">{{ $activeProducts }}</td>
                    </tr>
                    <tr>
                        <td>Produk Tidak Aktif (Stok = 0)</td>
                        <td class="text-center text-danger fw-bold">{{ $inactiveProducts }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Statistik Pesanan --}}
        <h5 class="fw-bold mb-3">Statistik Pesanan</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Status Pesanan</th>
                        <th class="text-center">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Pesanan</td>
                        <td class="text-center fw-bold">{{ $totalOrders }}</td>
                    </tr>
                    <tr>
                        <td>Menunggu Dikirim (Paid)</td>
                        <td class="text-center text-warning fw-bold">{{ $pendingOrders }}</td>
                    </tr>
                    <tr>
                        <td>Dalam Pengiriman (Shipped)</td>
                        <td class="text-center text-primary fw-bold">{{ $shippedOrders }}</td>
                    </tr>
                    <tr>
                        <td>Selesai (Completed)</td>
                        <td class="text-center text-success fw-bold">{{ $completedOrders }}</td>
                    </tr>
                    <tr>
                        <td>Dibatalkan (Cancelled)</td>
                        <td class="text-center text-danger fw-bold">{{ $cancelledOrders }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Statistik Lelang --}}
        <h5 class="fw-bold mb-3">Statistik Lelang</h5>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Lelang</h6>
                        <h2 class="fw-bold">{{ $totalAuctions }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Sedang Berlangsung</h6>
                        <h2 class="fw-bold text-primary">{{ $runningAuctions }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Selesai</h6>
                        <h2 class="fw-bold text-success">{{ $endedAuctions }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik Komplain & Review --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="fw-bold mb-3">Statistik Komplain</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Total Komplain</td>
                                <td class="text-center fw-bold">{{ $totalComplaints }}</td>
                            </tr>
                            <tr>
                                <td>Menunggu Tanggapan</td>
                                <td class="text-center text-warning fw-bold">{{ $waitingComplaints }}</td>
                            </tr>
                            <tr>
                                <td>Selesai</td>
                                <td class="text-center text-success fw-bold">{{ $resolvedComplaints }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="fw-bold mb-3">Statistik Review</h5>
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Review</h6>
                        <h2 class="fw-bold">{{ $totalReviews }}</h2>
                        <hr>
                        <h6 class="text-muted">Rating Rata-rata</h6>
                        <h2 class="fw-bold">
                            <i class="bi bi-star-fill text-warning"></i> 
                            {{ number_format($averageRating ?? 0, 1) }} / 5.0
                        </h2>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection