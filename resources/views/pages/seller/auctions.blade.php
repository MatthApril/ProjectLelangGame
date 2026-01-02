@extends('layouts.template')

@section('title', 'Lelang Seller | LelangGame')

@section('content')
    <div class="container mt-3">
        <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb mt-3">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard Seller</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Lelang</li>
            </ol>
        </nav>
        <h2 class="fw-bold">Daftar Lelang - {{ $shop->shop_name }}</h2>
        <hr>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <table class="table">
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Harga Awal</th>
                <th>Harga Terkini</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Status</th>
            </tr>
            @foreach ($auctions as $index => $auction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $auction->product->product_name ?? 'Produk dihapus' }}</td>
                    <td>Rp {{ number_format($auction->start_price ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($auction->current_price ?? 0, 0, ',', '.') }}</td>
                    <td>{{ optional($auction->start_time)->format('d M Y H:i') ?? '-' }}</td>
                    <td>{{ optional($auction->end_time)->format('d M Y H:i') ?? '-' }}</td>
                    <td>{{ ucfirst($auction->status) }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
