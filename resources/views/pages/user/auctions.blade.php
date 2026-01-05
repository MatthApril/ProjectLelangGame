@extends('layouts.template')

@section('title', 'Daftar Lelang | LelangGame')

@section('content')

<div class="container my-4">
    <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Semua Lelang</li>
        </ol>
    </nav>
    <h2 class="fw-semibold">Semua Lelang</h2>
    <hr>
    <input type="search" name="search" placeholder="Cari Lelang" value="{{ request('search') }}" class="form-control" autocomplete="off">
    <form method="GET" action="{{ route('auctions.index') }}">
        <div class="row">
            <div class="col-md-6 mt-3">
                <label>Game :</label>
                <select name="game_id" class="form-select">
                    <option value="">Semua Game</option>
                    @foreach($games as $game)
                        <option value="{{ $game->game_id }}" {{ request('game_id') == $game->game_id ? 'selected' : '' }}>
                            {{ $game->game_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mt-3">
                <label>Kategori :</label>
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ request('category_id') == $category->category_id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mt-3">
                <label>Harga Min :</label>
                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="IDR 0" class="form-control">
            </div>
            <div class="col-md-6 mt-3">
                <label>Harga Max :</label>
                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="IDR 1000000" class="form-control">
            </div>
        </div>
            <div class="mt-3">
                <label>Status : </label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Akan Dimulai</option>
                    <option value="running" {{ request('status') == 'running' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Istirahat</option>
                    <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-3 mt-3">
                    <div class="d-grid">
                        <a href="{{ route('auctions.index') }}" class="btn btn-outline-secondary rounded-5"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                    </div>
                </div>
                <div class="col-md-9 mt-3">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded-5"><i class="bi bi-funnel"></i> Filter</button>
                    </div>
                </div>
            </div>
    </form>
    <hr>

    <div class="row mt-4">
    @forelse ($auctions as $auction)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0 pb-3">
                <div class="position-relative">
                    <img src="{{ asset('storage/' . $auction->product->product_img) }}" 
                        class="card-img-top object-fit-cover" 
                        alt="{{ $auction->product->product_name }}" 
                        style="height: 200px;">
                    
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-danger d-flex align-items-center gap-1">
                            <span class="pulse-dot"></span> LIVE 
                        </span>
                    </div>

                    <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white text-center py-1">
                        <small><i class="bi bi-alarm"></i> Berakhir dalam: <span class="auction-timer fw-bold" data-end="{{ $auction->end_time }}">...</span></small>
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="fw-bold card-title text-truncate text-center" title="{{ $auction->product->product_name }}">
                        {{ $auction->product->product_name }}
                    </h5>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Harga Saat Ini:</small>
                        <span class="fw-bold text-primary fs-5">
                            Rp {{ number_format($auction->current_price, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-light rounded-circle border d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                            @if($auction->product->shop->shop_img)
                                <img src="{{ asset('storage/' . $auction->product->shop->shop_img) }}" alt="Shop Image" class="rounded-circle" style="width: 24px; height: 24px; object-fit: cover;">
                            @else
                                <i class="bi bi-person-fill text-secondary" style="font-size: 12px;"></i>
                            @endif
                        </div>
                        <small class="text-muted" style="font-size: 13px;">
                            Toko {{ $auction->product->shop->shop_name ?? 'Unknown' }} {!! Auth::user() == $auction->product->shop->owner ? '<b> (Anda)</b>' : '' !!}
                        </small>
                    </div>
                </div>

                <div class="card-footer bg-white border-top-0 pt-0">
                    @if (Auth::user() == $auction->product->shop->owner)
                        <a href="{{ route('seller.auctions.detail', $auction->auction_id) }}" class="btn btn-outline-success w-100 rounded-pill mb-2">
                            Detail Lelang <i class="bi bi-arrow-right"></i>
                        </a>
                    @else
                        <a href="{{ route('user.auctions.detail', $auction->auction_id) }}" class="btn btn-outline-primary w-100 rounded-pill">
                            Lihat Detail & Pasang Tawaran <i class="bi bi-arrow-right"></i>
                        </a>    
                    @endif
                </div>
            </div>
        </div>
        @empty
            <div class="text-center mb-5">
                <div>
                    <img src="{{ asset('images/auction-empty.jpeg') }}" alt="Lelang Empty" width="300">
                </div>
                <div>
                    <h5 class="fw-semibold">Wah lelang masih kosong!</h5>
                    <p>Silakan cek kembali nanti atau coba filter dengan kriteria lain.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Select all elements with class 'auction-timer'
        const timers = document.querySelectorAll('.auction-timer');

        setInterval(() => {
            const now = new Date().getTime();

            timers.forEach(timer => {
                const endTimeStr = timer.getAttribute('data-end');
                const endTime = new Date(endTimeStr).getTime();
                const distance = endTime - now;

                if (distance < 0) {
                    timer.innerHTML = "SELESAI";
                    timer.classList.add('text-danger');
                    return;
                }

                const d = Math.floor(distance / (1000 * 60 * 60 * 24));
                const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((distance % (1000 * 60)) / 1000);

                // Pad with zeros (e.g., 05m 02s)
                timer.innerHTML = `${d}h ${h < 10 ? '0'+h : h}j ${m < 10 ? '0'+m : m}m ${s < 10 ? '0'+s : s}d`;
            });
        }, 1000);
    });
</script>

@endsection
