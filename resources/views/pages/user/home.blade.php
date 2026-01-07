@extends('layouts.template')

@section('title', 'Beranda | LelangGame')

@section('content')
    <div class="sampul">
        <div class="container">
            <div class="row mb-4 px-2">
                <div class="col-md-3 mt-4 position-relative">
                    <img src="{{ asset('images/sampul/img_sampul_1.jpg') }}" alt="" style="width: 100%;"
                        class="img-fluid rounded shadow-lg">
                    <a href="#"
                        class="btn btn-outline-light position-absolute bottom-0 start-50 translate-middle-x mb-5 px-5 text-nowrap">Beli
                        Sekarang</a>
                </div>
                <div class="col-md-3 mt-4 position-relative">
                    <img src="{{ asset('images/sampul/img_sampul_2.jpg') }}" alt="" style="width: 100%;"
                        class="img-fluid rounded shadow-lg">
                    <a href="#"
                        class="btn btn-outline-light position-absolute bottom-0 start-50 translate-middle-x mb-5 px-5 text-nowrap">Beli
                        Sekarang</a>
                </div>
                <div class="col-md-3 mt-4 position-relative">
                    <img src="{{ asset('images/sampul/img_sampul_3.jpg') }}" alt="" style="width: 100%;"
                        class="img-fluid rounded shadow-lg">
                    <a href="#"
                        class="btn btn-outline-light position-absolute bottom-0 start-50 translate-middle-x mb-5 px-5 text-nowrap">Beli
                        Sekarang</a>
                </div>
                <div class="col-md-3 mt-4 position-relative">
                    <img src="{{ asset('images/sampul/img_sampul_4.jpg') }}" alt="" style="width: 100%;"
                        class="img-fluid rounded shadow-lg">
                    <a href="#"
                        class="btn btn-outline-light position-absolute bottom-0 start-50 translate-middle-x mb-5 px-5 text-nowrap">Beli
                        Sekarang</a>
                </div>
            </div>
            <h6 class="m-0 text-white text-center mb-4"><i class="bi bi-shield-check"></i> Transaksi Aman &nbsp;|&nbsp; <i
                    class="bi bi-cash-coin"></i> Garansi Uang Kembali &nbsp;|&nbsp; <i class="bi bi-headset"></i> Bantuan
                Customer Care</h6>
        </div>
    </div>
    <div class="container mt-5">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="fw-bold">Produk Terbaru</h2>
            <a href="{{ route('products.index') }}" class="text-decoration-none fw-semibold">Semua Produk <i
                    class="bi bi-chevron-right"></i></a>
        </div>
        <hr>
        <div class="row">
            @foreach ($latestProducts as $product)
                <div class="col-md-3 mt-3">
                    <div class="card">
                        @if ($product->product_img)
                            <img src="{{ asset('storage/' . $product->product_img) }}" alt=""
                                class="card-img-top product-img-16x9">
                        @endif
                        <div class="card-body">
                            <h5 class="fw-bold">{{ strlen($product->product_name) > 22 ? substr($product->product_name, 0, 22) . '...' : $product->product_name }}</h5>
                            <h5 class="text-primary fw-semibold">Rp{{ number_format($product->price, 0, ',', '.') }}</h5>
                            <p class="text-secondary">
                                <i class="bi bi-grid"></i> Kategori : {{ $product->category->category_name }} <br>
                                <i class="bi bi-controller"></i> Game : {{ $product->game->game_name }}
                            </p>
                            <div class="d-flex justify-content-between text-secondary">
                                <div>
                                    <i class="bi bi-box-seam"></i> Stok {{ $product->stok }}
                                </div>
                                <div>
                                    <i class="bi bi-star"></i> Rating {{ number_format($product->rating, 1) }}
                                </div>
                            </div>
                            <a href="{{ route('products.detail', $product->product_id) }}"
                                class="btn btn-primary btn-sm float-end mt-3">Lihat Produk <i
                                    class="bi bi-caret-right-fill"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="container mt-5">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="fw-bold">Produk Lelang</h2>
            <a href="{{ route('auctions.index') }}" class="text-decoration-none fw-semibold">Semua Lelang <i
                    class="bi bi-chevron-right"></i></a>
        </div>
        <hr>
        <div class="row">
            @foreach ($auctions as $auction)
                    @php
                    $statusConfig = match ($auction->status) {
                        'pending' => [
                            'badge' => 'secondary',
                            'text' => 'Akan Dimulai',
                            'icon' => 'bi-clock',
                            'harga' => 'Awal',
                        ],
                        'running' => [
                            'badge' => 'danger',
                            'text' => 'LIVE',
                            'icon' => 'bi-broadcast',
                            'harga' => 'Saat Ini',
                        ],
                        'ended' => [
                            'badge' => 'success',
                            'text' => 'Selesai',
                            'icon' => 'bi-check-circle',
                            'harga' => 'Akhir',
                        ],
                        default => [
                            'badge' => 'secondary',
                            'text' => ucfirst($auction->status),
                            'icon' => 'bi-question-circle',
                            'harga' => '',
                        ],
                    };
                @endphp
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm border-0 pb-3">
                        <div class="position-relative">
                            @if ($auction->product && $auction->product->product_img)
                                <img src="{{ asset('storage/' . $auction->product->product_img) }}"
                                    class="card-img-top object-fit-cover" alt="{{ $auction->product->product_name }}"
                                    style="height: 200px;">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" class="card-img-top object-fit-cover"
                                    alt="No Image" style="height: 200px;">
                            @endif

                            {{-- Status Badge --}}
                            <div class="position-absolute top-0 start-0 m-2">
                                @if ($auction->status == 'running')
                                    <span class="badge bg-danger d-flex align-items-center gap-1">
                                        <span class="pulse-dot"></span> LIVE
                                    </span>
                                @else
                                    <span class="badge bg-{{ $statusConfig['badge'] }}">
                                        <i class="bi {{ $statusConfig['icon'] }} me-1"></i>{{ $statusConfig['text'] }}
                                    </span>
                                @endif
                            </div>

                            {{-- Timer Bar --}}
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

                        <div class="card-body">
                            <h5 class="fw-bold card-title text-truncate text-center"
                                title="{{ $auction->product->product_name }}">
                                {{ $auction->product->product_name }}
                            </h5>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Harga {{ $statusConfig['harga'] }}:</small>
                                <span class="fw-bold text-primary fs-5">
                                    Rp {{ number_format($auction->current_price, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-light rounded-circle border d-flex align-items-center justify-content-center"
                                    style="width: 24px; height: 24px;">
                                    @if ($auction->product->shop->shop_img)
                                        <img src="{{ asset('storage/' . $auction->product->shop->shop_img) }}"
                                            alt="Shop Image" class="rounded-circle"
                                            style="width: 24px; height: 24px; object-fit: cover;">
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
                                <a href="{{ route('seller.auctions.detail', $auction->auction_id) }}"
                                    class="btn btn-outline-success w-100 rounded-pill mb-2">
                                    Detail Lelang <i class="bi bi-arrow-right"></i>
                                </a>
                            @else
                                <a href="{{ route('auctions.detail', $auction->auction_id) }}"
                                    class="btn btn-outline-primary w-100 rounded-pill">
                                    @if ($auction->status == 'running')
                                        Pasang Tawaran <i class="bi bi-hammer"></i>
                                    @elseif($auction->status == 'pending')
                                        Lihat Detail <i class="bi bi-arrow-right"></i>
                                    @else
                                        Lihat Detail <i class="bi bi-arrow-right"></i>
                                    @endif
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="container mt-5">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="fw-bold">Game Terpopuler</h2>
            <a href="{{ route('games.index') }}" class="text-decoration-none fw-semibold">Semua Game <i
                    class="bi bi-chevron-right"></i></a>
        </div>
        <hr>
        <div class="row">
            @foreach ($featuredGames as $game)
                <div class="col-md-2 mt-4">
                    <div class="card">
                        @if ($game->game_img)
                            <img src="{{ asset('storage/' . $game->game_img) }}" alt="" class="card-img-top shop-avatar w-100 h-100">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">{{ $game->game_name }}</h5>
                            <span class="text-secondary">{{ $game->products_count }} Produk Tersedia</span>
                            <a href="{{ route('games.detail', $game->game_id) }}"
                                class="btn btn-primary btn-sm mt-3 float-end">Lihat Produk <i
                                    class="bi bi-caret-right-fill"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="container mt-5">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="fw-bold">Toko Terpercaya</h2>
        </div>
        <hr>
        <div class="row">
            @foreach ($topShops as $shop)
                <div class="col-md-2 mt-4">
                    <div class="card">
                        @if ($shop->shop_img)
                            <img src="{{ asset('storage/' . $shop->shop_img) }}" alt=""
                                class="card-img-top card-img-top shop-avatar w-100 h-100">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">{{ strlen($shop->shop_name) > 12 ? substr($shop->shop_name, 0, 12) . '...' : $shop->shop_name }}</h5>
                            <p class="text-secondary m-0"><i class="bi bi-people-fill"></i> Pembeli
                                ({{ $shop->total_buyers }})
                            </p>
                            <p class="text-secondary m-0"><i class="bi bi-star"></i> Rating
                                {{ number_format($shop->shop_rating, 1) }}</p>
                            <a href="{{ route('shops.detail', $shop->shop_id) }}"
                                class="btn btn-primary btn-sm mt-3 float-end">Kunjungi Toko <i
                                    class="bi bi-caret-right-fill"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="container mt-5">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="fw-bold">Semua Kategori</h2>
        </div>
        <hr>
        <div class="row">
            @foreach ($categories as $category)
                <div class="col-md-2 mt-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold">{{ $category->category_name }}</h6>
                            <small class="text-secondary">{{ $category->products_count }} Produk</small>
                            <a href="{{ route('products.index', ['category_id' => $category->category_id]) }}"
                                class="btn btn-primary btn-sm mt-3 float-end">Semua Produk <i
                                    class="bi bi-caret-right-fill"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
