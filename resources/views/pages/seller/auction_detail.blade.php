@extends('layouts.template')

@section('title', 'Detail Lelang | LelangGame')

@section('content')
<div class="container my-4">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
            <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard Seller</a></li>
            <li class="breadcrumb-item"><a href="{{ route('seller.auctions.index') }}">Daftar Lelang</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Lelang</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-semibold mb-0">Detail Lelang</h2>
        @php
            $statusConfig = match($auction->status) {
                'pending' => ['class' => 'bg-secondary', 'text' => 'Akan Dimulai', 'icon' => 'bi-clock'],
                'running' => ['class' => 'bg-primary', 'text' => 'Berlangsung', 'icon' => 'bi-broadcast'],
                'ended' => ['class' => 'bg-success', 'text' => 'Selesai', 'icon' => 'bi-check-circle'],
                default => ['class' => 'bg-secondary', 'text' => ucfirst($auction->status), 'icon' => 'bi-question-circle'],
            };
        @endphp
        <span class="badge {{ $statusConfig['class'] }} fs-6 px-3 py-2">
            <i class="bi {{ $statusConfig['icon'] }} me-1"></i> {{ $statusConfig['text'] }}
        </span>
    </div>
    <hr>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Main 2-Column Layout --}}
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="position-relative">
                        @if($auction->product && $auction->product->product_img)
                            <img src="{{ asset('storage/products/' . $auction->product->product_img) }}"
                                 alt="{{ $auction->product->product_name }}"
                                 class="img-fluid rounded w-100"
                                 style="height: 350px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/no-image.png') }}"
                                 alt="No Image"
                                 class="img-fluid rounded w-100"
                                 style="height: 350px; object-fit: cover;">
                        @endif

                        {{-- Status Badge on Image --}}
                        @if($auction->status == 'running')
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-danger d-flex align-items-center gap-1 px-3 py-2">
                                    <span class="bg-white rounded-circle" style="width: 8px; height: 8px;"></span> LIVE
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Product Info Below Image --}}
                    <div class="py-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-controller me-1"></i>{{ $auction->product->game->game_name ?? '-' }}
                            </span>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-grid me-1"></i>{{ $auction->product->category->category_name ?? '-' }}
                            </span>
                        </div>
                        <h4 class="fw-bold mb-2">{{ $auction->product->product_name ?? 'Produk tidak tersedia' }}</h4>
                        @if ($auction->product->shop)
                            <p class="text-muted mb-0">
                                <i class="bi bi-box-seam"></i> Stok {{ $auction->product->stok }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Auction Details --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    {{-- Countdown Timer --}}
                    <div class="bg-dark text-white rounded-3 p-4 mb-4 text-center">
                        @if($auction->status == 'pending')
                            <p class="mb-2 text-white-50"><i class="bi bi-clock-history me-1"></i> Lelang dimulai dalam</p>
                            <h2 class="fw-bold mb-0 countdown-timer" data-time="{{ $auction->start_time }}" data-type="start">
                                --:--:--
                            </h2>
                        @elseif($auction->status == 'running')
                            <p class="mb-2 text-white-50"><i class="bi bi-alarm me-1"></i> Lelang berakhir dalam</p>
                            <h2 class="fw-bold mb-0 countdown-timer" data-time="{{ $auction->end_time }}" data-type="end">
                                --:--:--
                            </h2>
                        @else
                            <p class="mb-2 text-success"><i class="bi bi-check-circle me-1"></i> Lelang Telah Berakhir</p>
                            <h4 class="fw-bold mb-0 text-white">{{ $auction->end_time->format('d M Y, H:i') }} WIB</h4>
                        @endif
                    </div>

                    {{-- Price Section --}}
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <p class="text-muted small mb-1">Harga Awal</p>
                                <h5 class="fw-bold text-secondary mb-0">
                                    Rp {{ number_format($auction->start_price, 0, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3 text-center border border-primary">
                                <p class="text-primary small mb-1 fw-semibold">Tawaran Tertinggi</p>
                                <h5 class="fw-bold text-primary mb-0">
                                    Rp {{ number_format($auction->current_price, 0, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    {{-- Highest Bidder Info --}}
                    @if($auction->highestBid)
                        <div class="d-flex align-items-center bg-light rounded-3 p-3 mb-4">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 45px; height: 45px;">
                                 <i class="bi bi-person-fill text-white fs-5"></i>
                            </div>
                            <div>
                                <small class="text-muted">Penawar Tertinggi</small>
                                <p class="fw-semibold mb-0">{{ $auction->highestBid->user->username ?? 'Anonim' }}</p>
                            </div>
                            <div class="ms-auto text-end">
                                <small class="text-muted">Waktu Tawaran</small>
                                <p class="fw-semibold mb-0 small">{{ $auction->highestBid->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-light border text-center mb-4">
                            <i class="bi bi-info-circle me-1"></i> Belum ada penawaran
                        </div>
                    @endif

                    {{-- Action Zone (Dynamic based on status) --}}
                    <div class="border-top pt-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-lightning-charge me-1"></i> Status Lelang</h6>

                        @if($auction->status == 'pending')
                            <div class="alert alert-secondary d-flex align-items-center">
                                <i class="bi bi-hourglass-split fs-4 me-3"></i>
                                <div>
                                    <p class="mb-0 fw-semibold">Lelang Akan Dimulai</p>
                                    <small class="text-muted">Pada {{ $auction->start_time->format('d M Y, H:i') }} WIB</small>
                                </div>
                            </div>
                        @elseif($auction->status == 'running')
                            <div class="alert alert-primary d-flex align-items-center">
                                <i class="bi bi-broadcast fs-4 me-3"></i>
                                <div>
                                    <p class="mb-0 fw-semibold">Lelang Sedang Berlangsung</p>
                                    <small>Total {{ $auction->bids->count() }} penawaran masuk</small>
                                </div>
                            </div>
                        @else
                            {{-- Ended Status with Winner Info --}}
                            <div class="alert alert-success mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-trophy fs-4 me-3 text-warning"></i>
                                    <div>
                                        <p class="mb-0 fw-semibold">Lelang Telah Berakhir</p>
                                        <small>Selesai pada {{ $auction->end_time->format('d M Y, H:i') }} WIB</small>
                                    </div>
                                </div>
                            </div>

                            @if($auction->winner)
                                <div class="card bg-light border-success">
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold text-success">
                                            <i class="bi bi-trophy-fill me-1"></i> Pemenang Lelang
                                        </h6>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3"
                                                 style="width: 50px; height: 50px;">
                                                 <i class="bi bi-person-fill text-white fs-5"></i>
                                            </div>
                                            <div>
                                                <p class="fw-bold mb-0">{{ $auction->winner->user->username ?? 'Tidak diketahui' }}</p>
                                                <small class="text-muted">Harga Pemenang:</small>
                                                <span class="fw-bold text-success">
                                                    Rp {{ number_format($auction->winner->winning_price, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($auction->highestBid)
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Pemenang akan segera diumumkan
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Tidak ada pemenang (tidak ada penawaran)
                                </div>
                            @endif
                        @endif
                    </div>

                    {{-- Auction Time Info --}}
                    <div class="border-top pt-4 mt-3">
                        <div class="row g-2 small text-muted">
                            <div class="col-6">
                                <i class="bi bi-calendar-event me-1"></i> Mulai: {{ $auction->start_time->format('d M Y, H:i') }}
                            </div>
                            <div class="col-6 text-end">
                                <i class="bi bi-calendar-check me-1"></i> Selesai: {{ $auction->end_time->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Leaderboard: Riwayat Penawaran Tertinggi --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-list-ol me-2 text-primary"></i>Riwayat Penawaran Tertinggi
                </h5>
                <span class="badge bg-primary rounded-pill">
                    {{ $topBids->count() }} / {{ $auction->bids->count() }} Tawaran
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($topBids->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 80px;">Peringkat</th>
                                <th>Nama Penawar</th>
                                <th class="text-center">Waktu Penawaran</th>
                                <th class="text-end">Jumlah Tawaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topBids as $index => $bid)
                                <tr class="{{ $index == 0 ? 'table-warning' : '' }}">
                                    <td class="text-center">
                                        @if($index == 0)
                                            <span class="badge bg-warning text-dark fs-6">
                                                <i class="bi bi-trophy-fill"></i> 1
                                            </span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary fs-6">
                                                <i class="bi bi-award"></i> 2
                                            </span>
                                        @elseif($index == 2)
                                            <span class="badge bg-danger fs-6">
                                                <i class="bi bi-award"></i> 3
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 35px; height: 35px;">
                                                 <i class="bi bi-person-fill text-primary"></i>
                                            </div>
                                                <span class="fw-semibold">
                                                    @php
                                                        $username = $bid->user->username ?? 'Anonim';
                                                        $maskedName = strlen($username) > 6
                                                            ? substr($username, 0, 3) . '***' . substr($username, -2)
                                                            : $username;
                                                    @endphp
                                                    {{ $maskedName }}
                                                </span>
                                                @if($index == 0)
                                                    <span class="badge bg-success ms-2">Tertinggi</span>
                                                @endif
                                        </div>
                                    </td>
                                    <td class="text-center text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $bid->updated_at->format('d M Y, H:i:s') }}
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold {{ $index == 0 ? 'text-success fs-5' : 'text-primary' }}">
                                            Rp {{ number_format($bid->bid_price, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('images/empty-bid.png') }}" alt="No Bids" style="max-width: 150px; opacity: 0.5;" onerror="this.style.display='none'">
                    <i class="bi bi-inbox text-muted d-block" style="font-size: 4rem;"></i>
                    <h5 class="fw-semibold mt-3">Belum Ada Penawaran</h5>
                    <p class="text-muted">Penawaran akan muncul setelah lelang dimulai</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Product Description --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-file-text me-2 text-primary"></i>Deskripsi Produk
            </h5>
        </div>
        <div class="card-body">
            <p class="mb-0">{!! nl2br(e($auction->product->description ?? 'Tidak ada deskripsi')) !!}</p>
        </div>
    </div>
</div>

{{-- Countdown Timer Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timers = document.querySelectorAll('.countdown-timer');

        function updateTimers() {
            const now = new Date().getTime();

            timers.forEach(timer => {
                const timeStr = timer.getAttribute('data-time');
                const type = timer.getAttribute('data-type');
                const targetTime = new Date(timeStr).getTime();
                const distance = targetTime - now;

                if (distance < 0) {
                    if (type === 'start') {
                        timer.innerHTML = '<span class="text-success">DIMULAI!</span>';
                    } else {
                        timer.innerHTML = '<span class="text-danger">SELESAI</span>';
                    }
                    return;
                }

                const d = Math.floor(distance / (1000 * 60 * 60 * 24));
                const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((distance % (1000 * 60)) / 1000);

                let timeDisplay = '';
                if (d > 0) {
                    timeDisplay = `${d} hari ${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                } else {
                    timeDisplay = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                }

                timer.innerHTML = timeDisplay;
            });
        }

        updateTimers();
        setInterval(updateTimers, 1000);
    });
</script>
@endsection
