{{-- filepath: c:\kuliah\semester 3\BWP\project\ProjectLelangGame\resources\views\pages\seller\create.blade.php --}}
@extends('layouts.template')

@section('content')
<div class="px-5 my-3">
    <h5 class="fw-semibold text-dark">{{ $product ? 'Edit Produk' : 'Tambah Produk Baru' }}</h5><hr>

    <form action="{{ $product ? route('seller.products.update', $product->product_id) : route('seller.products.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @if($product)
            @method('PUT')
        @endif

        <div>
            <label for="product_name" class="fw-semibold">Nama Produk *</label><br>
            <input type="text" id="product_name" name="product_name" value="{{ old('product_name', $product->product_name ?? '') }}" placeholder="Masukkan nama item yang ingin kamu tambahkan" style="padding: 5px; width: 500px; border: 1px solid gray; border-radius: 5px;" required><br><br>
            @error('product_name')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="game_id" class="fw-semibold">Game *</label><br>
            <select id="game_id" name="game_id" style="padding: 5px; width: 500px; border: 1px solid gray; border-radius: 5px;" required>
                <option value="" style="padding: 5px;">Pilih Game</option>
                @foreach($games as $game)
                    <option value="{{ $game->game_id }}"
                            {{ old('game_id', $product->game_id ?? '') == $game->game_id ? 'selected' : '' }}>
                        {{ $game->game_name }}
                    </option>
                @endforeach
            </select><br><br>
            @error('game_id')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

       <div>
            <label for="category_id" class="fw-semibold">Kategori *</label><br>
            <select id="category_id" name="category_id" style="padding: 5px; width: 500px; border: 1px solid gray; border-radius: 5px;" required disabled>
                <option value="" style="padding: 5px;">Pilih Game Terlebih Dahulu</option>
            </select><br><br>
            @error('category_id')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="description" class="fw-semibold">Deskripsi *</label><br>
            <textarea id="description" name="description" rows="5" cols="62" style="padding: 5px; width: 500px; border: 1px solid gray; border-radius: 5px;" placeholder="Tulis deskripsi yang jelas dan mudah dimengerti oleh calon pembeli" required>{{ old('description', $product->description ?? '') }}</textarea>
            @error('description')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="stok" class="fw-semibold">Stok *</label><br>
            <input type="number" id="stok" name="stok" value="{{ old('stok', $product->stok ?? 1) }}" min="0" style="padding: 5px; width: 500px; border: 1px solid gray; border-radius: 5px;" required><br><br>
            @error('stok')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="price" class="fw-semibold">Harga (Rp) *</label><br>
            <input type="number" id="price" name="price" value="{{ old('price', $product->price ?? '') }}" min="0" style="padding: 5px; width: 500px; border: 1px solid gray; border-radius: 5px;" placeholder="IDR 0" required><br><br>
             @error('price')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="product_img" class="fw-semibold">Gambar Produk {{ $product ? '' : '*' }}</label><br>
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
            <a href="{{ route('seller.products.index') }}" class="text-decoration-none link-footer">Batal</a>
            <button type="submit" style="padding: 5px; border: 1px solid gray; border-radius: 5px;">{{ $product ? 'Update Produk' : 'Simpan Produk' }}</button>
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
                console.error('Error fetching categories:', error);
                categorySelect.innerHTML = '<option value="">Error loading categories</option>';
            });
    });

    if (gameSelect.value) {
        gameSelect.dispatchEvent(new Event('change'));
    }
</script>
@endsection
