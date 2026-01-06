{{-- filepath: c:\kuliah\semester 3\BWP\project\ProjectLelangGame\resources\views\pages\seller\create.blade.php --}}
@extends('layouts.template')

@section('title', $product ? 'Edit Produk | LelangGame' : 'Tambah Produk | LelangGame')

@section('content')
<div class="container mt-3">
    <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
            <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard Seller</a></li>
            <li class="breadcrumb-item"><a href="{{ route('seller.products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ $product ? 'Edit Produk' : 'Tambah Produk' }}
            </li>
        </ol>
    </nav>
    <h2 class="fw-semibold">{{ $product ? 'Edit Produk' : 'Tambah Produk' }}</h2>
    <hr>
    @error('product_img')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @enderror
    @error('product_name')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @enderror
    @error('game_id')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @enderror
    @error('category_id')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @enderror
    @error('description')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @enderror
    @error('stok')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @enderror
    @error('price')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @enderror
    <form action="{{ $product ? route('seller.products.update', $product->product_id) : route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($product)
            @method('PUT')
        @endif
        <div class="row">
            <div class="col-md-12 mb-3">
                @if($product && $product->product_img)
                    <div class="mb-3">
                        <label>Gambar Saat Ini :</label><br>
                        <img src="{{ asset('storage/' . $product->product_img) }}"alt="Gambar Produk" width="300" class="shadow rounded img-fluid">
                    </div>
                @endif
                <label>Gambar Produk {{ $product ? '' : '*' }} <span class="text-secondary">(Disarankan Ukuran Gambar 16 : 9)</span></label>
                <input type="file" id="product_img" name="product_img" accept="image/*" {{ $product ? '' : 'required' }} class="form-control">
                <i>Format: JPG, PNG, JPEG. Max: 2MB</i>
            </div>
            <div class="col-md-12 mb-3">
                <label>Nama Produk *</label>
                <br>
                <input type="text" id="product_name" name="product_name" value="{{ old('product_name', $product->product_name ?? '') }}" placeholder="Nama Produk Yang Mau Ditambahkan" class="form-control" autocomplete="off" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Game *</label>
                <select id="game_id" name="game_id" class="form-select" required>
                    <option value="">Pilih Game</option>
                    @foreach($games as $game)
                        <option value="{{ $game->game_id }}"
                                {{ old('game_id', $product->game_id ?? '') == $game->game_id ? 'selected' : '' }}>
                            {{ $game->game_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Kategori *</label>
                <select id="category_id" name="category_id" class="form-select" required disabled>
                    <option value="">Pilih Game Terlebih Dahulu</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Stok *</label>
                <input type="number" id="stok" name="stok" value="{{ old('stok', $product->stok ?? 1) }}" min="0" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Harga (Rp) *</label>
                <input type="number" id="price" name="price" value="{{ old('price', $product->price ?? '') }}" min="0" class="form-control" placeholder="IDR 0" required>
            </div>
            <div class="col-md-12 mb-3">
                <label>Deskripsi</label><br>
                <textarea rows="5" id="description" name="description" class="form-control" placeholder="Tuliskan Deskripsi Produk Yang Ingin Di Jual">{{ old('description', $product->description ?? '') }}</textarea>
            </div>
        </div>

        <div class="text-end mt-2">
            <a href="{{ route('seller.products.index') }}" class="btn btn-danger"><i class="bi bi-x-lg"></i> Batal</a>
            <button type="submit"
                class="btn {{ $product ? 'btn-warning' : 'btn-primary' }}">
                {!! $product ? '<i class="bi bi-pencil-square"></i> Edit Produk' : '<i class="bi bi-plus-lg"></i> Tambah Produk' !!}
            </button>
        </div>
    </form>
</div>

<script>
    const gameSelect = document.getElementById('game_id');
    const categorySelect = document.getElementById('category_id');
    const selectedCategoryId = "{{ old('category_id', $product->category_id ?? '') }}";

    gameSelect.addEventListener('change', function() {
        const gameId = this.value;

        if (!gameId) {
            categorySelect.disabled = true;
            categorySelect.innerHTML = '<option value="">Pilih Game Terlebih Dahulu</option>';
            return;
        }

        fetch(`/seller/games/${gameId}/categories`)
            .then(response => response.json())
            .then(categories => {
                categorySelect.disabled = false;
                categorySelect.innerHTML = '<option value="">Pilih Kategori</option>';

                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.category_id;
                    option.textContent = category.category_name;

                    if (selectedCategoryId == category.category_id) {
                        option.selected = true;
                    }

                    categorySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Gagal Menemukan Kategori:', error);
                categorySelect.innerHTML = '<option value="">Gagal Loading Kategori</option>';
            });
    });

    if (gameSelect.value) {
        gameSelect.dispatchEvent(new Event('change'));
    }
</script>
@endsection
