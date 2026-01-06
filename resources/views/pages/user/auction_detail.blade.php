@extends('layouts.template')

@section('title', 'Detail Lelang | LelangGame')

@section('content')
    <div class="container my-4">
        {{-- Breadcrumb --}}
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('auctions.index') }}">Daftar Lelang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Lelang</li>
            </ol>
        </nav>

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-semibold mb-0">Detail Lelang</h2>
            @php
                $statusConfig = match ($auction->status) {
                    'pending' => ['class' => 'bg-secondary', 'text' => 'Akan Dimulai', 'icon' => 'bi-clock'],
                    'running' => ['class' => 'bg-primary', 'text' => 'Berlangsung', 'icon' => 'bi-broadcast'],
                    'ended' => ['class' => 'bg-success', 'text' => 'Selesai', 'icon' => 'bi-check-circle'],
                    default => [
                        'class' => 'bg-secondary',
                        'text' => ucfirst($auction->status),
                        'icon' => 'bi-question-circle',
                    ],
                };
            @endphp
            <span class="badge {{ $statusConfig['class'] }} fs-6 px-3 py-2">
                <i class="bi {{ $statusConfig['icon'] }} me-1"></i> {{ $statusConfig['text'] }}
            </span>
        </div>
        <hr>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @error('bid_price')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @enderror

        {{-- Main 2-Column Layout --}}
        <div class="row g-4">
            {{-- Left Column: Product Image --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="position-relative">
                            @if ($auction->product && $auction->product->product_img)
                                <img src="{{ asset('storage/' . $auction->product->product_img) }}"
                                    alt="{{ $auction->product->product_name }}" class="img-fluid rounded w-100"
                                    style="height: 350px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="No Image"
                                    class="img-fluid rounded w-100" style="height: 350px; object-fit: cover;">
                            @endif

                            {{-- Status Badge on Image --}}
                            @if ($auction->status == 'running')
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-danger d-flex align-items-center gap-1 px-3 py-2">
                                        <span class="pulse-dot"></span> LIVE
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
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-box-seam me-1"></i>{{ $auction->product->stok }}
                                </span>
                            </div>
                            <h4 class="fw-bold mb-2">{{ $auction->product->product_name ?? 'Produk tidak tersedia' }}</h4>
                            @if ($auction->product->shop)
                                <div class="text-muted mb-0 d-flex justify-content-between">
                                    <span><i class="bi bi-shop me-1"></i>
                                        {{ $auction->product->shop->shop_name ?? 'Toko tidak tersedia' }}</span>
                                    <span><i class="bi bi-star me-1"></i> Rating
                                        {{ number_format($auction->product->rating, 1) }}</span>
                                </div>
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
                            @if ($auction->status == 'pending')
                                <p class="mb-2 text-white-50"><i class="bi bi-clock-history me-1"></i> Lelang dimulai dalam
                                </p>
                                <h2 class="fw-bold mb-0 countdown-timer" data-time="{{ $auction->start_time }}"
                                    data-type="start">
                                    --:--:--
                                </h2>
                            @elseif($auction->status == 'running')
                                <p class="mb-2 text-white-50"><i class="bi bi-alarm me-1"></i> Lelang berakhir dalam</p>
                                <h2 class="fw-bold mb-0 countdown-timer" data-time="{{ $auction->end_time }}"
                                    data-type="end">
                                    --:--:--
                                </h2>
                            @else
                                <p class="mb-2 text-success"><i class="bi bi-check-circle me-1"></i> Lelang Telah Berakhir
                                </p>
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
                        @if ($auction->highestBid)
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
                                    <p class="fw-semibold mb-0 small">
                                        {{ $auction->highestBid->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-light border text-center mb-4">
                                <i class="bi bi-info-circle me-1"></i> Belum ada penawaran
                            </div>
                        @endif

                        {{-- Action Zone (Dynamic based on status) --}}
                        <div class="border-top pt-4">
                            @if ($auction->status == 'pending')
                                <div class="alert alert-secondary d-flex align-items-center mb-0">
                                    <i class="bi bi-hourglass-split fs-4 me-3"></i>
                                    <div>
                                        <p class="mb-0 fw-semibold">Lelang Akan Dimulai</p>
                                        <small class="text-muted">Pada {{ $auction->start_time->format('d M Y, H:i') }}
                                            WIB</small>
                                    </div>
                                </div>
                            @elseif($auction->status == 'ended')
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

                                @if ($auction->winner)
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
                                                    <p class="fw-bold mb-0">
                                                        {{ $auction->winner->user->username ?? 'Tidak diketahui' }}</p>
                                                    <small class="text-muted">Harga Pemenang:</small>
                                                    <span class="fw-bold text-success">
                                                        Rp
                                                        {{ number_format($auction->winner->winning_price, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($auction->highestBid)
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Pemenang akan segera diumumkan
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-0">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Tidak ada pemenang (tidak ada penawaran)
                                    </div>
                                @endif
                            @else
                                {{-- Running: Brief status message --}}
                                <div class="alert alert-primary d-flex align-items-center mb-0">
                                    <i class="bi bi-broadcast fs-4 me-3"></i>
                                    <div>
                                        <p class="mb-0 fw-semibold">Lelang Sedang Berlangsung</p>
                                        <small>Pasang tawaran Anda di bawah ini</small>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Auction Time Info --}}
                        <div class="border-top pt-4 mt-4">
                            <div class="row g-2 small text-muted">
                                <div class="col-6">
                                    <i class="bi bi-calendar-event me-1"></i> Mulai:
                                    {{ $auction->start_time->format('d M Y, H:i') }}
                                </div>
                                <div class="col-6 text-end">
                                    <i class="bi bi-calendar-check me-1"></i> Selesai:
                                    {{ $auction->end_time->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bidding Form Section (Full Width - Only when running) --}}
        @if ($auction->status == 'running')
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-hammer me-2"></i>Pasang Tawaran
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('user.auctions.bid', ['auctionId' => $auction->auction_id]) }}"
                        method="post">
                        @csrf

                        <div class="row g-4">
                            {{-- Left: Price Info --}}
                            <div class="col-md-4">
                                <div
                                    class="bg-success bg-opacity-10 rounded-3 p-4 text-center border border-success h-100 d-flex flex-column justify-content-center">
                                    <small class="text-success d-block mb-2 fw-semibold">Minimal Tawaran Berikutnya</small>
                                    <h3 class="text-success fw-bold mb-0">
                                        Rp {{ number_format(($auction->current_price ?? 0) + 1000, 0, ',', '.') }}
                                    </h3>
                                </div>
                            </div>

                            {{-- Right: Bid Controls --}}
                            <div class="col-md-8">
                                {{-- Bid Input --}}
                                <div class="mb-3">
                                    <label for="bidAmount" class="form-label fw-semibold">
                                        <i class="bi bi-cash-stack me-1"></i>Jumlah Tawaran Anda
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-primary text-white fw-bold">Rp</span>
                                        <input type="number" class="form-control form-control-lg fw-bold" id="bidAmount"
                                            name="bid_price" min="{{ $auction->current_price + 1000 }}"
                                            value="{{ $auction->current_price + 1000 }}" readonly required>
                                    </div>
                                </div>

                                {{-- Quick Add Buttons --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted small">
                                        <i class="bi bi-lightning-fill me-1"></i>Tambah Cepat
                                    </label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-secondary btn-sm quick-add"
                                            data-amount="1000">
                                            +Rp 1rb
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm quick-add"
                                            data-amount="5000">
                                            +Rp 5rb
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm quick-add"
                                            data-amount="10000">
                                            +Rp 10rb
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm quick-add"
                                            data-amount="25000">
                                            +Rp 25rb
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm quick-add"
                                            data-amount="50000">
                                            +Rp 50rb
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm quick-add"
                                            data-amount="100000">
                                            +Rp 100rb
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick="resetBid()">
                                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                                        </button>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-hammer me-2"></i>Tawar Sekarang
                                </button>
                            </div>
                        </div>

                        {{-- Info Text --}}
                        <div class="alert alert-light border mt-4 mb-0 small" role="alert">
                            <i class="bi bi-info-circle-fill text-primary me-1"></i>
                            Tawaran harus lebih tinggi dari harga terkini minimal <strong>Rp 1.000</strong>.
                            Tawaran yang sudah diajukan tidak dapat dibatalkan.
                        </div>
                    </form>
                </div>
            </div>
        @endif

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

    {{-- Pulse Animation CSS --}}
    <style>
        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #fff;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.2);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    {{-- Scripts --}}
    <script>
        const minBid = {{ $auction->current_price + 1000 }};

        function resetBid() {
            document.getElementById('bidAmount').value = minBid;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Quick add buttons
            document.querySelectorAll('.quick-add').forEach(button => {
                button.addEventListener('click', function() {
                    const bidAmountInput = document.getElementById('bidAmount');
                    const amount = parseInt(this.dataset.amount);
                    let currentBid = parseInt(bidAmountInput.value);
                    currentBid += amount;
                    bidAmountInput.value = currentBid;
                });
            });

            // Countdown Timer
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
                        timeDisplay =
                            `${d} hari ${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                    } else {
                        timeDisplay =
                            `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                    }

                    timer.innerHTML = timeDisplay;
                });
            }

            updateTimers();
            setInterval(updateTimers, 1000);
        });
    </script>
@endsection
