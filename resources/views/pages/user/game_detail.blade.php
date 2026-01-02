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

    <div class="card mb-5 p-3">
        <div class="row d-flex align-items-center">
            <div class="col-md-4 text-center">
                @if($game->game_img)
                    <img src="{{ asset('storage/' . $game->game_img) }}" alt="{{ $game->game_name }}" width="300">
                @endif
            </div>
            
            <div class="col-md-8">
            <h1 class="fw-semibold">Game : {{ $game->game_name }}</h1>
            @if($categories->count() > 0)
                <h4 class="fw-semibold">Kategori :</h4>
                @foreach($categories as $category)
                    <button class="btn btn-outline-primary btn-sm mt-2" disabled>{{ $category->category_name }}</button>
                @endforeach
                @endif
            </div>
        </div>
    </div>

    <h2 class="fw-semibold">Produk Untuk {{ $game->game_name }}</h2>
    <hr>
    @if($products->count() > 0)
    <div class="row">
        @forelse($products as $product)
        <div class="col-md-3 mt-2">
            <a href="{{ route('products.detail', $product->product_id) }}" class="text-decoration-none text-dark">
                <div class="card card-products">
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
                    </div>
                </div>
            </a>
        </div>
        @empty
        {{-- <h4 class="fw-semibold mt-4 text-center">Tidak Ada Produk Ditemukan</h4> --}}
        @endforelse
        {{ $products->links() }}
    </div>

    @else
        <h4 class="fw-semibold text-center mt-4">Tidak Ada Produk Ditemukan</h4>
    @endif
</div>
@endsection
