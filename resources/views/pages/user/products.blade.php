@extends('layouts.template')

@section('title', 'Semua Produk | LelangGame')

@section('content')
<div class="container">
    <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Semua Produk</li>
        </ol>
    </nav>
    <h2 class="fw-semibold">Semua Produk</h2>
    <hr>
    <input type="search" name="search" placeholder="Cari Produk" value="{{ request('search') }}" class="form-control" autocomplete="off">
    <form method="GET" action="{{ route('products.index') }}">
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
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-5"><i class="bi bi-arrow-clockwise"></i> Reset</a>
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
    <div class="row">
        @forelse($products as $product)
        <div class="col-md-3 mt-3">
            {{-- <a href="{{ route('products.detail', $product->product_id) }}" class="text-decoration-none text-dark"> --}}
                <div class="card">
                    @if($product->product_img)
                        <img src="{{ asset('storage/' . $product->product_img) }}" alt="{{ $product->product_name }}" class="card-img-top">
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
                        <a href="{{ route('products.detail', $product->product_id) }}" class="btn btn-primary btn-sm float-end mt-2">Lihat Produk <i class="bi bi-caret-right-fill"></i></a>
                    </div>
                </div>
            {{-- </a> --}}
        </div>
        @empty
        <h4 class="fw-semibold mt-5 text-center">Tidak Ada Produk Ditemukan</h4>
        @endforelse
    </div>

    <br>
    {{ $products->links() }}
</div>
@endsection
