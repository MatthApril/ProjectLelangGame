{{-- filepath: c:\kuliah\semester 3\BWP\project\ProjectLelangGame\resources\views\pages\seller\product.blade.php --}}
@extends('layouts.template')

@section('title', 'Kelola Produk | LelangGame')

@section('content')
<div class="container-fluid mt-3">
    <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kelola Produk</li>
        </ol>
    </nav>
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="fw-semibold">Kelola Produk</h2>
        <a href="{{ route('seller.products.create') }}" class="btn btn-outline-primary rounded rounded-5"><i class="bi bi-plus-lg"></i> Tambah Produk</a>
    </div>
    <hr>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div>
        <h5 class="fw-semibold">Filter Produk Toko Anda</h5>
        <form action="{{ route('seller.products.index') }}" method="GET">
        <div class="row">
            <div class="col-md-4 mb-3">
                <!-- Filter Kategori -->
                <select name="category_id" onchange="this.form.submit()" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}"
                                {{ request('category_id') == $category->category_id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8 mb-3">
                <div class="input-group">
                    {{-- <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span> --}}
                    <input type="search" class="form-control" name="search" placeholder="Cari Nama Produk Yang Dimiliki" aria-label="Search" autocomplete="off" value="{{ request('search') }}" autofocus>
                    <button type="submit" class="btn btn-light"><i class="bi bi-search"></i> Cari</button>
                </div>
            </div>
            <div class="text-end">
                @if(request('category_id') || request('search'))
                    <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise"></i> Reset Filter</a>
                @endif
            </div>
        </div>
        </form>
        <hr>
        {{-- <a href="{{ route('seller.products.create') }}">
            <button style="padding: 5px; width: 500px; border: 1px solid gray; border-radius: 5px;">Tambah Produk Baru</button>
        </a> --}}
    </div>

    <div>
        @forelse($products as $product)
            <div class="col-md-3 mt-3">
                {{-- <a href="{{ route('products.detail', $product->product_id) }}" class="text-decoration-none text-dark"> --}}
                    <div class="card">
                        @if($product->product_img)
                            <img src="{{ asset('storage/' . $product->product_img) }}" alt="{{ $product->product_name }}" class="card-img-top">
                        @endif
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $product->product_name }}</h5>

                            @if($product->deleted_at)
                                <span class="badge bg-danger">Dihapus</span>
                            @elseif($product->category?->deleted_at)
                                <span class="badge bg-warning">Kategori Dihapus</span>
                            @elseif($product->game?->deleted_at)
                                <span class="badge bg-warning">Game Dihapus</span>
                            @else
                                <span class="badge bg-success">Aktif</span>
                            @endif
                            <h5 class="text-primary fw-semibold">Rp{{ number_format($product->price, 0, ',', '.') }}</h5>
                            <p class="text-secondary">
                                <i class="bi bi-grid"></i> Kategori : <span class="{{ $product->category?->deleted_at ? 'text-danger text-decoration-line-through' : '' }}">
                                    {{ $product->category->category_name }}
                                </span> 
                                <br>
                                <i class="bi bi-controller"></i> Game : <span class="{{ $product->game?->deleted_at ? 'text-danger text-decoration-line-through' : '' }}">
                                    {{ $product->game->game_name }}
                                </span>
                            </p>
                            <div class="d-flex justify-content-between text-secondary">
                                <div>
                                    <i class="bi bi-box-seam"></i> Stok {{ $product->stok }}
                                </div>
                                <div>
                                    <i class="bi bi-star"></i> Rating {{ number_format($product->rating, 1) }}
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-2">
                                 @if($product->deleted_at)
                                    <form action="{{ route('seller.products.restore', $product->product_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Kembalikan</button>
                                    </form>
                                @else
                                <a href="{{ route('seller.products.edit', $product->product_id) }}" class="rounded btn btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('seller.products.destroy', $product->product_id) }}" method="POST" onsubmit="return confirm('Yakin Ingin Menghapus Produk Ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash3"></i> Hapus</button>
                                </form>
                                @endif
                            </div>
                            {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p> --}}
                        </div>
                    </div>
                {{-- </a> --}}
            </div>
            {{-- <div>
                @if($product->product_img)
                    <img src="{{ asset('storage/' . $product->product_img) }}" alt="{{ $product->product_name }}" width="200" height="200">
                @else
                    <div>No Image</div>
                @endif

                <div>
                    <h3>{{ $product->product_name }}</h3>
                    <p>Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <p>Stok: {{ $product->stok }}</p>
                    <p>Kategori: {{ $product->category->category_name ?? '-' }}</p>
                    <p>Game: {{ $product->game->game_name ?? '-' }}</p>
                    <p>Rating: {{ $product->rating }}</p>
                </div>

                <div>
                    <a href="{{ route('seller.products.edit', $product->product_id) }}">
                        <button>Edit</button>
                    </a>
                    <form action="{{ route('seller.products.destroy', $product->product_id) }}" method="POST" onsubmit="return confirm('Yakin Ingin Menghapus Produk Ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </div>
            </div> --}}
        @empty
            <div class="text-center mb-5">
                <div>
                    <img src="{{ asset('images/product-empty.png') }}" alt="Product Empty" width="300">
                </div>
                <div>
                    <h5 class="fw-semibold">Wah Produk Tidak Ditemukan!</h5>
                    <p>Mau Jual Apa? Tambahkan Produk Sekarang.</p>
                    <a href="{{ route('seller.products.create') }}" class="btn btn-outline-primary rounded rounded-5"><i class="bi bi-plus-lg"></i> Tambahkan Produk</a>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
