@extends('layouts.template')

@section('content')

<div>
    <a href="{{ route('products.index') }}">← Kembali ke Daftar Produk</a>

    <br>
    <br>

    <div>
        @if ($product->product_img)
            <img src="{{ asset('storage/' . $product->product_img) }}" alt="{{ $product->product_name }}" width="300">
        @endif

        <h1>{{ $product->product_name }}</h1>
        <p>Game: {{ $product->game->game_name }}</p>
        <p>Kategori: {{ $product->category->category_name }}</p>
        <p><strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong></p>
        <p>Stok: {{ $product->stok }}</p>
        <p>Rating: {{ number_format($product->rating, 1) }} ⭐</p>
        <p><a href="{{ route('shops.detail', $product->shop->shop_id ) }}">{{ $product->shop->shop_name }}</a></p>

        @auth
            <form action="{{ route('user.cart.add', $product->product_id) }}" method="POST">
                @csrf
                <label>Jumlah:</label>
                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stok }}">
                <button type="submit">Tambah ke Keranjang</button>
            </form>
        @else
            <p><a href="{{ route('login') }}">Login</a> untuk membeli produk ini</p>
        @endauth
    </div>

    <br><br>

     @if($relatedProducts->count() > 0)
    <div>
        <h3>Produk Terkait</h3>
        @foreach($relatedProducts as $related)
        <div>
            @if($related->product_img)
                <img src="{{ asset('storage/' . $related->product_img) }}" alt="{{ $related->product_name }}" width="150">
            @endif
            <h4>{{ $related->product_name }}</h4>
            <p>Rp {{ number_format($related->price, 0, ',', '.') }}</p>
            <a href="{{ route('products.detail', $related->product_id) }}">Lihat Detail</a>
        </div>
        <br>
        @endforeach
    </div>
    @endif

</div>
