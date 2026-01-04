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
                    <img src="{{ asset('storage/' . $shop->shop_img) }}" alt="{{ $shop->shop_name }}" width="150">
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
            <h4 class="fw-semibold">Info Toko</h4>
            <div class="card p-3">
                <p class="text-secondary"><i class="bi bi-person-fill"></i> Pemilik ({{ $shop->owner->username }})</p>
                @auth
                    <form action="{{ route('user.chat.show', $shop->owner->user_id) }}" method="GET">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-chat-left-fill"></i> Chat Pemilik Toko</button>
                        </div>
                    </form>
                @else
                    <p><a href="{{ route('login') }}">Login</a> untuk chat dengan pemilik toko.</p>
                @endauth
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <h4 class="fw-semibold">Rating <i class="bi bi-star-fill text-warning"></i> {{ number_format($shop->shop_rating, 1) }} / 5.0</h4>
            <div class="card p-3">
                <div class="d-flex gap-2">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                </div>
                <div class="d-flex gap-2">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star text-warning"></i>
                </div>
                <div class="d-flex gap-2">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star text-warning"></i>
                    <i class="bi bi-star text-warning"></i>
                </div>
                <div class="d-flex gap-2">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star text-warning"></i>
                    <i class="bi bi-star text-warning"></i>
                    <i class="bi bi-star text-warning"></i>
                </div>
                <div class="d-flex gap-2">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star text-warning"></i>
                    <i class="bi bi-star text-warning"></i>
                    <i class="bi bi-star text-warning"></i>
                    <i class="bi bi-star text-warning"></i>
                </div>
            </div>
        </div>
    </div>

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
    {{ $products->links() }}
    @else
    <div class="text-center">
        <div>
            <img src="{{ asset('images/product-empty.png') }}" alt="Product Empty" width="300">
        </div>
        <div>
            <h5 class="fw-semibold">Wah toko ini belum memiliki produk.</h5>
            <p>Silahkan hubungi toko untuk informasi produk lebih lanjut.</p>
            <a href="#" class="btn btn-outline-primary rounded rounded-5"><i class="bi bi-chat-left-fill"></i> Chat Pemilik Toko</a>
        </div>
    </div>
    @endif
</div>
@endsection
