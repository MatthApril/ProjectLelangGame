@extends('layouts.template')

@section('content')
<div>
    <a href="{{ route('games.index') }}">← Kembali ke Daftar Game</a>

    <br><br>

    <div>
        @if($game->game_img)
            <img src="{{ asset('storage/' . $game->game_img) }}" alt="{{ $game->game_name }}" width="300">
        @endif
        <h1>{{ $game->game_name }}</h1>
    </div>

    @if($categories->count() > 0)
    <div>
        <h3>Kategori:</h3>
        @foreach($categories as $category)
            <span>{{ $category->category_name }}</span>
            @if(!$loop->last) | @endif
        @endforeach
    </div>
    @endif

    <br>

    <h2>Produk untuk {{ $game->game_name }}</h2>

    @if($products->count() > 0)
    <div>
        @foreach($products as $product)
        <div>
            @if($product->product_img)
                <img src="{{ asset('storage/' . $product->product_img) }}" alt="{{ $product->product_name }}" width="200">
            @endif
            <h4>{{ $product->product_name }}</h4>
            <p>Kategori: {{ $product->category->category_name }}</p>
            <p><strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong></p>
            <p>Stok: {{ $product->stok }}</p>
            <p>Toko: {{ $product->shop->shop_name }}</p>
            <a href="{{ route('products.detail', $product->product_id) }}">Lihat Detail</a>
        </div>
        <br>
        @endforeach
    </div>

    <br>
    {{ $products->links() }}
    @else
    <p>Belum ada produk untuk game ini</p>
    @endif
</div>
@endsection
