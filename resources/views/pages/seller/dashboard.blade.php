@extends('layouts.template')

@section('title', 'Beranda | LelangGame')

@section('content')
    <div class="px-5 my-3">
        <h5 class="fw-semibold text-dark">Dashboard Seller - {{ $shop->shop_name }}</h5><hr>

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
                        <a href="{{ route('seller.chat.show', ['userId' => $user->user_id]) }}">
                            <button class="btn btn-primary">
                                Chat
                            </button>
                        </a>
                </tr>
            @endforeach
        </table>
    </div>
    
        <h2>Keuangan Toko</h2>
        <table border="1" cellpadding="10">
            <tr>
                <td style="border: 1px solid gray; padding: 5px;"><strong>Saldo Toko</strong></td>
                <td style="border: 1px solid gray; padding: 5px;"><strong>Transaksi Berjalan</strong></td>
            </tr>
            <tr>
                <td style="border: 1px solid gray; padding: 5px;">Rp {{ number_format($shopBalance, 0, ',', '.') }}</td>
                <td style="border: 1px solid gray; padding: 5px;">Rp {{ number_format($runningTransactions, 0, ',', '.') }}</td>
            </tr>
        </table>

        <br>

        <h6 class="fw-bold">Statistik Produk</h6>
        <table border="1" cellpadding="10">
            <tr>
                <td style="border: 1px solid gray; padding: 5px;"><strong>Total Produk</strong></td>
                <td style="border: 1px solid gray; padding: 5px;">{{ $totalProducts }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid gray; padding: 5px;"><strong>Produk Tersedia</strong></td>
                <td style="border: 1px solid gray; padding: 5px;">{{ $activeProducts }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid gray; padding: 5px;"><strong>Total Pesanan</strong></td>
                <td style="border: 1px solid gray; padding: 5px;">{{ $totalOrders }}</td>
            </tr>
        </table>

        <br>

        <h6 class="fw-bold">Informasi Toko</h6>
        <table border="1" cellpadding="10">
            <tr>
                <td style="border: 1px solid gray; padding: 5px;"><strong>Status Toko</strong></td>
                <td style="border: 1px solid gray; padding: 5px;">{{ ucfirst($shop->status) }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid gray; padding: 5px;"><strong>Rating</strong></td>
                <td style="border: 1px solid gray; padding: 5px;">{{ number_format($shop->shop_rating, 1) }} / 5.0</td>
            </tr>
            <tr>
                <td style="border: 1px solid gray; padding: 5px;"><strong>Jam Operasional</strong></td>
                <td style="border: 1px solid gray; padding: 5px;">{{ $shop->open_hour }} - {{ $shop->close_hour }}</td>
            </tr>
        </table>

        @if ($shop->shop_img)
            <br>
            <h6 class="fw-bold">Gambar Toko</h6>
            <img src="{{ asset('storage/' . $shop->shop_img) }}" alt="Shop Image" width="300">
        @endif

        <br><br>

        <h6 class="fw-bold">Menu Cepat</h6>
        <p>
            <a href="{{ route('seller.products.index') }}" class="text-decoration-none link-footer">Kelola Produk</a> |
            <a href="{{ route('seller.products.create') }}" class="text-decoration-none link-footer">Tambah Produk</a> |
            <a href="{{ route('profile') }}" class="text-decoration-none link-footer">Profile</a>
        </p>
    </div>
@endsection
