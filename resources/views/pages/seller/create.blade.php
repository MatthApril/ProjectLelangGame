{{-- filepath: c:\kuliah\semester 3\BWP\project\ProjectLelangGame\resources\views\pages\seller\create.blade.php --}}
@extends('layouts.template')

@section('content')
<div>
    <h1>{{ $product ? 'Edit Produk' : 'Tambah Produk Baru' }}</h1>

    <form action="{{ $product ? route('seller.products.update', $product->product_id) : route('seller.products.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @if($product)
            @method('PUT')
        @endif

        <div>
            <label for="product_name">Nama Produk *</label>
            <input type="text" id="product_name" name="product_name" value="{{ old('product_name', $product->product_name ?? '') }}" required>
            @error('product_name')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="game_id">Game *</label>
            <select id="game_id" name="game_id" required>
                <option value="">Pilih Game</option>
                @foreach($games as $game)
                    <option value="{{ $game->game_id }}"
                            {{ old('game_id', $product->game_id ?? '') == $game->game_id ? 'selected' : '' }}>
                        {{ $game->game_name }}
                    </option>
                @endforeach
            </select>
            @error('game_id')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="category_id">Kategori *</label>
            <select id="category_id" name="category_id" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->category_id }}"
                            {{ old('category_id', $product->category_id ?? '') == $category->category_id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
             @error('category_id')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="description">Deskripsi *</label>
            <textarea id="description" name="description" rows="5" required>{{ old('description', $product->description ?? '') }}</textarea>
            @error('description')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="stok">Stok *</label>
            <input type="number" id="stok" name="stok" value="{{ old('stok', $product->stok ?? 1) }}" min="0" required>
            @error('stok')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="price">Harga (Rp) *</label>
            <input type="number" id="price" name="price" value="{{ old('price', $product->price ?? '') }}" min="0" required>
             @error('price')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="product_img">Gambar Produk {{ $product ? '' : '*' }}</label>
            <input type="file" id="product_img"name="product_img" accept="image/*" {{ $product ? '' : 'required' }}>
            <p>Format: JPG, PNG, JPEG. Max: 2MB</p>
             @error('product_img')
                <span style="color: red;">{{ $message }}</span>
            @enderror
            @if($product && $product->product_img)
                <div>
                    <p>Gambar saat ini:</p>
                    <img src="{{ asset('storage/' . $product->product_img) }}"alt="Product Image" width="200">
                </div>
            @endif
        </div>

        <div>
            <a href="{{ route('seller.products.index') }}">Batal</a>
            <button type="submit">{{ $product ? 'Update Produk' : 'Simpan Produk' }}</button>
        </div>
    </form>
</div>
@endsection
