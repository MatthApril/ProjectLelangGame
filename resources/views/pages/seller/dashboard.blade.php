@extends('layouts.template')

@section('title', 'Beranda | LelangGame')

@section('content')
    <div>
        <h1>Dashboard Seller - {{ $shop->shop_name }}</h1>

        @if (session('success'))
            <div>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <hr>

    <div class="container-fluid">
        <h3>Owners</h3>
        <table border="1" class="table table-striped">
            <tr>
                <th>No</th>
                <th>Owner</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
            @foreach ($users as $index => $user)
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

        <h2>Keuangan Toko</h2>
        <table border="1" cellpadding="10">
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

        <h2>Statistik Produk</h2>
        <table border="1" cellpadding="10">
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

        <h2>Informasi Toko</h2>
        <table border="1" cellpadding="10">
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
            <h3>Gambar Toko</h3>
            <img src="{{ asset('storage/' . $shop->shop_img) }}" alt="Shop Image" width="300">
        @endif

        <br><br>

        <h2>Menu Cepat</h2>
        <p>
            <a href="{{ route('seller.products.index') }}">Kelola Produk</a> |
            <a href="{{ route('seller.products.create') }}">Tambah Produk</a> |
            <a href="{{ route('profile') }}">Profile</a>
        </p>
    </div>
@endsection
