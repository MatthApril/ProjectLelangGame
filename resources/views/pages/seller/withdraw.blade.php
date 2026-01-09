@extends('layouts.template')

@section('title', 'Penarikan Saldo | LelangGame')

@section('content')
    <a href="#" id="btnScrollTop" class="btn btn-primary position-fixed rounded-5 btn-lg fw-bold bottom-0 end-0 mb-5 me-3 fs-3" style="z-index: 9999; display: none;"><i class="bi bi-arrow-up"></i></a>
    <div class="container">
        <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb mt-3">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
                <li class="breadcrumb-item active" aria-current="page">Penarikan Saldo</li>
            </ol>
        </nav>


        <h2 class="fw-semibold">Penarikan Saldo</h2>
        <hr>
        <h4 class="fw-semibold">Saldo Anda : Rp{{ number_format($shop->shop_balance, 0, ',', '.') }}</h4>
        <hr>

        <div class="card mb-5">
            <div class="card-body">
                <form action="{{ route('seller.withdraws.request') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label for="amount" class="form-label">Ajukan Jumlah Penarikan (Rp)</label>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount"
                            name="amount" value="{{ old('amount') }}" min="10000" placeholder="Min : IDR 10000"
                            required>
                        @error('amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success float-end">Ajukan Penarikan <i
                            class="bi bi-caret-right-fill"></i></button>
                </form>
            </div>
        </div>

        <h2 class="fw-semibold">Riwayat Penarikan Saldo</h2>
        <hr>
        @if ($withdraws->count() > 0)
            <div class="row">
                @foreach ($withdraws as $withdraw)
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-2">
                                    @if ($withdraw->status === 'waiting')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock-history"></i> Menunggu
                                        </span>
                                    @elseif ($withdraw->status === 'done')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-lg"></i> Selesai
                                        </span>
                                    @elseif ($withdraw->status === 'rejected')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-lg"></i> Ditolak
                                        </span>
                                    @endif
                                </div>
                                <h4 class="card-title text-primary fw-bold mb-2">
                                    Rp{{ number_format($withdraw->amount, 0, ',', '.') }}
                                </h4>
                                <p class="card-text text-muted mb-0">
                                    <i class="bi bi-calendar3"></i> {{ $withdraw->created_at->format('d M Y, H:i') }} WIB
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info text-center" role="alert">
                <i class="bi bi-info-circle"></i> Belum Ada Riwayat Penarikan Saldo
            </div>
        @endif

    @endsection
