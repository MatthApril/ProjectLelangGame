@extends('layouts.template')

@section('title', 'Dashboard Seller | LelangGame')

@section('content')
    <div class="container-fluid mt-3">
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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <hr>

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Status Toko:
                            @if($shop->status === 'open')
                                <span class="badge bg-success">Buka</span>
                            @else
                                <span class="badge bg-danger">Tutup</span>
                            @endif
                        </h5>
                        <small class="text-muted">Jam Operasional: {{ $shop->open_hour }} - {{ $shop->close_hour }}</small>
                    </div>
                    <div>
                        <form action="{{ route('seller.shop.toggle-status') }}" method="POST">
                            @csrf
                            @if($shop->status === 'open')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menutup toko?')">
                                    <i class="bi bi-x-circle"></i> Tutup Toko
                                </button>
                            @else
                                <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin membuka toko?')">
                                    <i class="bi bi-check-circle"></i> Buka Toko
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

       
        <table border="1" cellpadding="10">
        <h4 class="fw-bold">Menu Cepat</h4>
        <div class="d-flex gap-2 mb-3">
            <a href="{{ route('seller.products.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2"><i
                    class="bi bi-box-seam"></i> Kelola Produk</a>
            <a href="{{ route('seller.products.create') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2"><i
                    class="bi bi-plus-lg"></i> Tambah Produk</a>
            <a href="{{ route('seller.reviews.index') }}" class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2">Lihat Ulasan</a>
            <a href="{{ route('seller.auctions.create.form') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2"><i
                    class="bi bi-plus-lg"></i> Buat Lelang</a>
            <a href="{{ route('seller.auctions.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2"><i
                    class="bi bi-plus-lg"></i> Daftar Lelang</a>
            <a href="{{ route('seller.incoming_orders.index') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2"><i
                    class="bi bi-plus-lg"></i> Daftar Pesanan Masuk</a>
            <a href="{{ route('profile') }}"
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2"><i
                    class="bi bi-person-fill"></i> Profile</a>
            <a href="{{ route('seller.complaints.index') }}" 
                class="btn btn-sm d-flex align-items-center btn-outline-primary text-decoration-none gap-2">
                    Kelola Complaints</a>
        </div>
        <hr>
        <div>
            <h6 class="fw-bold">Owners</h6>
            <table border="1" class="table table-striped">
                <tr>
                    <th>No</th>
                    <th>Owner</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            @foreach( $users as $index => $user )
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <form action="{{ route('user.chat.show', $user->user_id) }}" method="post">
                            @csrf
                            <button class="btn btn-primary" type="submit">
                                Chat
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

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

        {{-- @if ($shop->shop_img)
            <br>
            <h6 class="fw-bold">Gambar Toko</h6>
            <img src="{{ asset('storage/' . $shop->shop_img) }}" alt="Shop Image" width="300">
        @endif --}}
    </div>
@endsection
