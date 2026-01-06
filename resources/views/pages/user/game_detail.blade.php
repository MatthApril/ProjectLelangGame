@extends('layouts.template')

@section('content')
<div class="container">
    <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('games.index') }}">Semua Game</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Game</li>
        </ol>
    </nav>

    <div class="card mb-5">
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-md-4 text-center">
                    @if($game->game_img)
                        <img src="{{ asset('storage/' . $game->game_img) }}" alt="" width="300" class="img-fluid">
                    @endif
                </div>
                
                <div class="col-md-8">
                <h2 class="fw-semibold">Game {{ $game->game_name }}</h2>
                @if($categories->count() > 0)
                    <div class="d-flex align-items-center gap-2">
                        @foreach($categories as $category)
                            <button class="btn btn-outline-primary btn-sm" disabled>{{ $category->category_name }}</button>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <h2 class="fw-semibold">Produk Untuk {{ $game->game_name }}</h2>
    <hr>
    <input type="search" name="search" placeholder="Cari Produk {{ $game->game_name }}" value="{{ request('search') }}" class="form-control" autocomplete="off">
    <form method="GET" action="{{ route('games.detail', $game->game_id) }}">
        <div class="row">
            <div class="col-md-12 mt-3">
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
                        <a href="{{ route('games.detail', $game->game_id) }}" class="btn btn-outline-secondary rounded-5"><i class="bi bi-arrow-clockwise"></i> Reset</a>
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
    <div class="row">
        @forelse($products as $product)
        <div class="col-md-3 mt-2">
            {{-- <a href="{{ route('products.detail', $product->product_id) }}" class="text-decoration-none text-dark"> --}}
                <div class="card">
                    @if($product->product_img)
                        <img 
                            src="{{ asset('storage/' . $product->product_img) }}" 
                            alt="" 
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
        @empty
        {{-- <h4 class="fw-semibold mt-4 text-center">Tidak Ada Produk Ditemukan</h4> --}}
        @endforelse
        {{ $products->links() }}
    </div>

    @else
        <div class="text-center">
            <div>
                <img src="{{ asset('images/product-empty.png') }}" alt="Product Empty" width="300" class="img-fluid">
            </div>
            <div>
                <h5 class="fw-semibold">Wah produk tidak ditemukan.</h5>
                <p>Belum ada produk yang sesuai dengan kriteria pencarian Anda.</p>
            </div>
        </div>
    @endif
</div>
@endsection
