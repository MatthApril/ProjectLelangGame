@extends('layouts.template')

@section('title', $shop->shop_name . ' | LelangGame')

@section('content')
<div class="container">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb mt-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('user.home') }}">Beranda</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index') }}">Semua Produk</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Toko {{ $shop->shop_name }}
                </li>
            </ol>
        </nav>
    <div class="row">
        <div class="col-md-4 d-flex align-items-start gap-3 mt-3">
            @if($shop->shop_img)
                <div>
                    <img src="{{ asset('storage/' . $shop->shop_img) }}" alt="{{ $shop->shop_name }}" width="150" class="shop-avatar">
                </div>
            @endif
            <div>
                <h4 class="fw-semibold">{{ $shop->shop_name }}</h4>
                <p class="text-secondary mb-3"><i class="bi bi-clock"></i> {{ $shop->open_hour }} - {{ $shop->close_hour }}</p>
                @if($shop->status === 'closed')
                    <button class="btn btn-outline-danger btn-sm" disabled>Toko Tutup</button>
                @else
                    <button class="btn btn-outline-success btn-sm" disabled>Toko Buka</button>
                @endif
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="fw-semibold m-0">Info Toko</h5>
            </div>
            <div class="card p-3">
                <p class="text-secondary m-0"><i class="bi bi-person-fill"></i> Pemilik ({{ $shop->owner->username }})</p>
                <p class="text-secondary m-0 my-2"><i class="bi bi-people-fill"></i> Pembeli ({{ $totalBuyers }}) Orang</p>
                <p class="text-secondary"><i class="bi bi-box-seam"></i> Produk Terjual ({{ $totalProductsSold }})</p>
                @auth
                    @if(Auth::user()->user_id != $shop->owner_id)
                        <form action="{{ route('chat.open', $shop->owner->user_id) }}" method="GET">
                            <div class="d-grid">
                                <input type="hidden" name="return_url" value="{{ route('shops.detail', $shop->shop_id) }}">
                                <input type="hidden" name="return_label" value="Kembali ke Toko">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bi bi-chat-left-fill"></i> Chat Pemilik Toko
                                </button>
                            </div>
                        </form>
                    @endif
                @else
                    <p class="text-secondary m-0 mb-2">Silahkan masuk terlebih dahulu jika ingin chat dengan pemilik toko.</p>
                    <div class="d-grid">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary"><i class="bi bi-box-arrow-in-right"></i> Masuk</a>
                    </div>
                @endauth
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="fw-semibold m-0">Rating <i class="bi bi-star-fill text-warning"></i> {{ number_format($shop->shop_rating, 1) }} / 5.0</h5>
                <a href="#" class="text-decoration-none fw-semibold m-0">Semua Ulasan</a>
            </div>
            <div class="card p-3">
                @if($totalReviews > 0)
                    @for($i = 5; $i >= 1; $i--)
                        <div class="d-flex gap-2 align-items-center mb-2">
                            @for($j = 1; $j <= 5; $j++)
                                @if($j <= $i)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-warning"></i>
                                @endif
                            @endfor
                            
                            <div class="progress flex-grow-1 mx-2" role="progressbar" 
                                aria-label="Rating {{ $i }} stars" 
                                aria-valuenow="{{ $ratingPercentages[$i] }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100" 
                                style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: {{ $ratingPercentages[$i] }}%"></div>
                            </div>
                            
                            <span class="text-secondary" style="min-width: 50px;">{{ number_format($ratingStats[$i]) }}</span>
                        </div>
                    @endfor
                    
                    <hr class="my-2">
                    <div class="text-center">
                        <small class="text-secondary">Total {{ number_format($totalReviews) }} Ulasan</small>
                    </div>
                @else
                    <div class="text-center text-secondary">
                        <i class="bi bi-star" style="font-size: 48px;"></i>
                        <p class="mb-0 mt-2">Belum Ada Ulasan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <hr>
    <input type="search" name="search" placeholder="Cari Produk" value="{{ request('search') }}" class="form-control" autocomplete="off">
    <form method="GET" action="{{ route('shops.detail', $shop->shop_id) }}">
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
                <label>Urutkan :</label>
                <select name="sort" class="form-select">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-3 mt-3">
                    <div class="d-grid">
                        <a href="{{ route('shops.detail', $shop->shop_id) }}" class="btn btn-outline-secondary rounded-5"><i class="bi bi-arrow-clockwise"></i> Reset</a>
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
    @if($products->count() > 0)
    <div class="row mt-3">
        @foreach($products as $product)
        <div class="col-md-3 mt-3">
            {{-- <a href="{{ route('products.detail', $product->product_id) }}" class="text-decoration-none text-dark"> --}}
                <div class="card">
                    @if($product->product_img)
                        <img 
                            src="{{ asset('storage/' . $product->product_img) }}" 
                            alt="{{ $product->product_name }}" 
                            class="card-img-top product-img-16x9"
                        >
                    @endif
                    <div class="card-body">
                        <h5 class="fw-bold">{{ $product->product_name }}</h5>
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
                        <a href="{{ route('products.detail', $product->product_id) }}" class="btn btn-primary btn-sm float-end mt-3">Lihat Produk <i class="bi bi-caret-right-fill"></i></a>
                    </div>
                </div>
            {{-- </a> --}}
        </div>
        @endforeach
    </div>
    <div class="mt-3">
        {{ $products->links() }}
    </div>
    @else
        @auth
            @if (Auth::user()->user_id == $shop->owner_id)
                <div class="text-center">
                    <div>
                        <img src="{{ asset('images/product-empty.png') }}" alt="Product Empty" width="300">
                    </div>
                    <div>
                        <h5 class="fw-semibold">Toko anda belum memiliki produk.</h5>
                        <p>Silahkan menambahkan produk pada halaman profil anda.</p>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <div>
                        <img src="{{ asset('images/product-empty.png') }}" alt="Product Empty" width="300">
                    </div>
                    <div>
                        <h5 class="fw-semibold">Wah toko ini belum memiliki produk yang sesuai.</h5>
                        <p>Coba ubah filter pencarian Anda atau hubungi pemilik toko.</p>
                    </div>
                </div>
            @endif
        @else
            <div class="text-center">
                <div>
                    <img src="{{ asset('images/product-empty.png') }}" alt="Product Empty" width="300">
                </div>
                <div>
                    <h5 class="fw-semibold">Wah toko ini belum memiliki produk yang sesuai.</h5>
                    <p>Coba ubah filter pencarian Anda.</p>
                </div>
            </div>
        @endauth
    @endif
</div>
@endsection
