@extends('layouts.template')

@section('title', 'Lelang Seller | LelangGame')

@section('content')
    <div class="container my-4">
        <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard Seller</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Lelang</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-semibold">Kelola Lelang</h2>
            <a href="{{ route('seller.auctions.create.form') }}" class="btn btn-outline-primary rounded rounded-5"><i
                    class="bi bi-plus-lg"></i> Tambah Lelang</a>
        </div>
        <hr>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            @forelse ($auctions as $index => $auction)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm border-0 pb-3">
                        @if ($auction->product && $auction->product->product_img)
                            @php
                                $statusClass = match ($auction->status) {
                                    'pending' => 'secondary',
                                    'running' => 'primary',
                                    'ended' => 'success',
                                    default => '',
                                };
                                $statusHarga = match ($auction->status) {
                                    'pending' => 'Awal',
                                    'running' => 'Terkini',
                                    'ended' => 'Akhir',
                                    default => '',
                                };
                                $statusIndo = match ($auction->status) {
                                    'pending' => 'Akan Dimulai',
                                    'running' => 'Berlangsung',
                                    'ended' => 'Selesai',
                                    default => '',
                                };
                            @endphp
                            <div class="position-relative auction-img-container">
                                @if ($auction->product && $auction->product->product_img)
                                    <img src="{{ asset('storage/' . $auction->product->product_img) }}"
                                        class="auction-img border-top rounded" alt="{{ $auction->product->product_name }}">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" class="auction-img border-top rounded"
                                        alt="No Image">
                                @endif
                                <span
                                    class="position-absolute top-0 start-0 m-2 badge bg-{{ $statusClass }} text-white text-capitalize">
                                    {{ $statusIndo }}
                                </span>
                                @if ($auction->status == 'pending')
                                    <div
                                        class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white text-center py-1">
                                        <small><i class="bi bi-hourglass-split"></i> Dimulai dalam:
                                            <span class="auction-timer fw-bold" data-time="{{ $auction->start_time }}"
                                                data-type="start">...</span>
                                        </small>
                                    </div>
                                @elseif($auction->status == 'running')
                                    <div
                                        class="position-absolute bottom-0 start-0 w-100 bg-primary bg-opacity-75 text-white text-center py-1">
                                        <small><i class="bi bi-alarm"></i> Berakhir dalam:
                                            <span class="auction-timer fw-bold" data-time="{{ $auction->end_time }}"
                                                data-type="end">...</span>
                                        </small>
                                    </div>
                                @elseif($auction->status == 'ended')
                                    <div
                                        class="position-absolute bottom-0 start-0 w-100 bg-success bg-opacity-75 text-white text-center py-1">
                                        <small><i class="bi bi-check-circle"></i> Lelang Selesai</small>
                                    </div>
                                @endif
                            </div>
                        @else
                            <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="No Image">
                        @endif
                        <div class="card-body d-flex flex-column p-3 border-top border-5 border-{{ $statusClass }}">
                            <h5 class="card-title fw-semibold text-center mb-2 text-truncate"
                                title="{{ $auction->product->product_name ?? 'Produk dihapus' }}">
                                {{ $auction->product->product_name ?? 'Produk dihapus' }}
                            </h5>

                            <div class="d-flex justify-content-between align-items-center my-2">
                                <span class="text-muted small">Harga {{ $statusHarga }}</span>
                                <span class="text-{{ $statusClass }} fw-semibold">Rp
                                    {{ number_format($auction->current_price, 0, ',', '.') }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center" style="min-height: 32px;">
                                @if ($auction->highestBid)
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-trophy-fill text-warning"></i>
                                        <small class="text-muted text-truncate"
                                            style="max-width: 100px;">&nbsp;{{ $auction->highestBid->user->username ?? 'Unknown' }}</small>
                                    </div>
                                    <div style="width: 85px;"></div>
                                @elseif($auction->status == 'running')
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-person-x text-muted"></i>
                                        <small class="text-muted">&nbsp;Belum ada tawaran</small>
                                    </div>
                                    <div style="width: 85px;"></div>
                                @elseif($auction->status == 'ended')
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                        <small class="text-muted">&nbsp;Tidak ada tawaran</small>
                                    </div>
                                    <div style="width: 85px;"></div>
                                @else
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-person-x text-muted"></i>
                                        <small class="text-muted">&nbsp;Belum Mulai</small>
                                    </div>
                                    <div style="width: 85px;"></div>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top-0 p-3 pt-0">
                            <a href="{{ route('seller.auctions.detail', $auction->auction_id) }}"
                                class="btn btn-outline-primary w-100 rounded-pill">
                                <i class="bi bi-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center mb-5">
                    <div>
                        <img src="{{ asset('images/auction-empty.jpeg') }}" alt="Lelang Empty" width="300">
                    </div>
                    <div>
                        <h5 class="fw-semibold">Wah Lelang Tidak ditemukan!</h5>
                        <p>Mau mulai lelang? Tambahkan produk dan buat lelang sekarang.</p>
                        <a href="{{ route('seller.auctions.create.form') }}"
                            class="btn btn-outline-primary rounded rounded-5"><i class="bi bi-plus-lg"></i> Tambahkan
                            Lelang</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timers = document.querySelectorAll('.auction-timer');

            function updateTimers() {
                const now = new Date().getTime();

                timers.forEach(timer => {
                    const timeStr = timer.getAttribute('data-time');
                    const type = timer.getAttribute('data-type');
                    const targetTime = new Date(timeStr).getTime();
                    const distance = targetTime - now;

                    if (distance < 0) {
                        if (type === 'start') {
                            timer.innerHTML = "DIMULAI!";
                            timer.classList.add('text-success');
                        } else {
                            timer.innerHTML = "SELESAI";
                            timer.classList.add('text-danger');
                        }
                        return;
                    }

                    const d = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const s = Math.floor((distance % (1000 * 60)) / 1000);

                    if (d > 0) {
                        timer.innerHTML =
                            `${d}h ${h < 10 ? '0'+h : h}j ${m < 10 ? '0'+m : m}m ${s < 10 ? '0'+s : s}d`;
                    } else if (h > 0) {
                        timer.innerHTML = `${h}j ${m < 10 ? '0'+m : m}m ${s < 10 ? '0'+s : s}d`;
                    } else {
                        timer.innerHTML = `${m < 10 ? '0'+m : m}m ${s < 10 ? '0'+s : s}d`;
                    }
                });
            }

            updateTimers();
            setInterval(updateTimers, 1000);
        });
    </script>
@endsection
