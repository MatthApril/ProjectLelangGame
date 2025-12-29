{{-- filepath: c:\kuliah\semester 3\BWP\project\ProjectLelangGame\resources\views\pages\seller\product.blade.php --}}
@extends('layouts.template')

@section('content')
<div class="px-5 my-3">
    <h5 class="fw-semibold text-dark">Daftar Produk</h5><hr>

    @if(session('success'))
        <div>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div>
        <form action="{{ route('seller.products.index') }}" method="GET">
            <!-- Filter Kategori -->
            <select name="category_id" onchange="this.form.submit()" style="padding: 5px; width: 500px; border: 1px solid gray; border-radius: 5px;">
                <option value="" style="padding: 5px;">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->category_id }}"
                            {{ request('category_id') == $category->category_id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select><br><br>
            <input type="text" name="search" placeholder="Cari nama produk" value="{{ request('search') }}" style="padding: 5px; width: 500px; border: 1px solid gray; border-radius: 5px;"><br><br>

            {{-- <button type="submit">Cari</button> --}}

            @if(request('category_id') || request('search'))
                <a href="{{ route('seller.products.index') }}">Reset Filter</a>
            @endif
        </form>

        <a href="{{ route('seller.products.create') }}">
            <button style="padding: 5px; width: 500px; border: 1px solid gray; border-radius: 5px;">Tambah Produk Baru</button>
        </a><br><br>
    </div>

    <div>
        @forelse($products as $product)
            <div>
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
                    <form action="{{ route('seller.products.destroy', $product->product_id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p>Belum ada produk. <br><a href="{{ route('seller.products.create') }}" class="text-decoration-none link-footer">Tambah produk pertama</a></p>
        @endforelse
    </div>

</div>
@endsection
