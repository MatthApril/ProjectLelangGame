@extends('layouts.template')

@section('title', 'Beranda | LelangGame')

@section('content')
    <div>
        <form action="{{ route('products.index') }}" method="GET">
            <input type="text" name="search" placeholder="Cari produk...">
            <button type="submit">Cari</button>
        </form>

        <br><br>

        <section>
            <h2>Game Populer</h2>
            <div>
                @foreach ($featuredGames as $game)
                    <div>
                        @if ($game->game_img)
                            <img src="{{ asset('storage/' . $game->game_img) }}" alt="{{ $game->game_name }}" width="200">
                        @endif
                        <h3>{{ $game->game_name }}</h3>
                        <p>{{ $game->products_count }} Produk</p>
                        <a href="{{ route('games.detail', $game->game_id) }}">Lihat Detail</a>
                    </div>
                    <br>
                @endforeach
            </div>
            <a href="{{ route('games.index') }}">Lihat Semua Game →</a>
        </section>

        <br><br>

        <section>
            <h2>Produk Terbaru</h2>
            <div>
                @foreach ($latestProducts as $product)
                    <div>
                        @if ($product->product_img)
                            <img src="{{ asset('storage/' . $product->product_img) }}" alt="{{ $product->product_name }}"
                                width="200">
                        @endif
                        <h4>{{ $product->product_name }}</h4>
                        <p>{{ $product->game->game_name }} - {{ $product->category->category_name }}</p>
                        <p><strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong></p>
                        <p>Stok: {{ $product->stok }} | Rating: {{ number_format($product->rating, 1) }} ⭐</p>
                        <a href="{{ route('products.detail', $product->product_id) }}">Lihat Detail</a>
                    </div>
                    <br>
                @endforeach
            </div>
            <a href="{{ route('products.index') }}">Lihat Semua Produk →</a>
        </section>

        <br><br>

        <section>
            <h2>Toko Terpercaya</h2>
            <div>
                @foreach ($topShops as $shop)
                    <div>
                        @if ($shop->shop_img)
                            <img src="{{ asset('storage/' . $shop->shop_img) }}" alt="{{ $shop->shop_name }}"
                                width="100">
                        @endif
                        <h4>{{ $shop->shop_name }}</h4>
                        <p>Rating: {{ number_format($shop->shop_rating, 1) }} ⭐</p>
                        <a href="{{ route('shops.detail', $shop->shop_id) }}">Kunjungi Toko</a>
                    </div>
                    <br>
                @endforeach
            </div>
        </section>
    </div>
@endsection
