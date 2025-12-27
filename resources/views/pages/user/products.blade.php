@extends('layouts.template')

@section('content')
<div>
    <h1>Daftar Produk</h1>

    <form method="GET" action="{{ route('products.index') }}">
        <div>
            <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}">
        </div>

        <br>

        <div>
            <label>Game:</label>
            <select name="game_id">
                <option value="">Semua Game</option>
                @foreach($games as $game)
                    <option value="{{ $game->game_id }}" {{ request('game_id') == $game->game_id ? 'selected' : '' }}>
                        {{ $game->game_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Kategori:</label>
            <select name="category_id">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->category_id }}" {{ request('category_id') == $category->category_id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <div>
            <label>Harga Min:</label>
            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0">

            <label>Harga Max:</label>
            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="1000000">
        </div>

        <br>

        <div>
            <label>Urutkan:</label>
            <select name="sort">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
            </select>
        </div>

        <br>

        <button type="submit">Filter</button>
        <a href="{{ route('products.index') }}">Reset</a>
    </form>

    <br><br>

    <div>
        @forelse($products as $product)
        <div>
            @if($product->product_img)
                <img src="{{ asset('storage/' . $product->product_img) }}" alt="{{ $product->product_name }}" width="200">
            @endif
            <h4>{{ $product->product_name }}</h4>
            <p>Game: {{ $product->game->game_name }}</p>
            <p>Kategori: {{ $product->category->category_name }}</p>
            <p><strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong></p>
            <p>Stok: {{ $product->stok }} | Rating: {{ number_format($product->rating, 1) }} ⭐</p>
            <p>Toko: <a href="{{ route('shops.detail', $product->shop->shop_id) }}">{{ $product->shop->shop_name }}</a></p>
            <a href="{{ route('products.detail', $product->product_id) }}">Lihat Detail</a>
        </div>
        <br>
        @empty
        <p>Tidak ada produk ditemukan</p>
        @endforelse
    </div>

    <br>
    {{ $products->links() }}
</div>
@endsection
