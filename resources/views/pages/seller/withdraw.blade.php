@extends('layouts.template')

@section('title', 'Review Produk Toko | LelangGame')

@section('content')

    <div class="container">
        <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb mt-3">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard Seller</a></li>
                <li class="breadcrumb-item active" aria-current="page">Penarikan Dana</li>
            </ol>
        </nav>


        <h2 class="fw-semibold">Penarikan Saldo</h2>
        <hr>
        <h4>Saldo Anda: Rp {{ number_format($shop->shop_balance, 0, ',', '.') }}</h4>
        <hr>

        <h2>Ajukan Penarikan Saldo</h2>
        <form action="{{ route('seller.withdraws.request') }}" method="POST" class="mb-4">
            @csrf
            <div class="mb-3">
                <label for="amount" class="form-label">Jumlah Penarikan (Rp)</label>
                <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount"
                    name="amount" value="{{ old('amount') }}" min="10000" required>
                @error('amount')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Ajukan Penarikan</button>
        </form>

        <h2>Riwayat Penarikan Saldo</h2>
        @forelse ($withdraws as $withdraw)
            <div class="card mb-3">
                <div class="card-body">
                    <p class="card-text"><strong>Jumlah:</strong> Rp {{ number_format($withdraw->amount, 0, ',', '.') }}</p>
                    <p class="card-text"><strong>Status:</strong>
                        @if ($withdraw->status === 'waiting')
                            <span class="badge bg-warning text-dark">Waiting</span>
                        @elseif ($withdraw->status === 'done')
                            <span class="badge bg-success">Completed</span>
                        @elseif ($withdraw->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </p>
                    <p class="card-text"><small class="text-muted">Tanggal Permintaan:
                            {{ $withdraw->created_at->format('d M Y H:i') }}</small></p>
                </div>
            </div>
        @empty
            <p>Belum ada riwayat penarikan saldo.</p>
        @endforelse

    @endsection
