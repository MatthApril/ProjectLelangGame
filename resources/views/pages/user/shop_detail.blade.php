@extends('layouts.template')

@section('content')
<div>
    <div>
        @if($shop->shop_img)
            <img src="{{ asset('storage/' . $shop->shop_img) }}" alt="{{ $shop->shop_name }}" width="150">
        @endif
        <h1>{{ $shop->shop_name }}</h1>
        @if($shop->status === 'closed')
            <p style="color: red;">Toko Sedang Tutup</p>
        @else
            <p style="color: green;">Toko Buka</p>
        @endif
        <p>Rating: {{ number_format($shop->shop_rating, 1) }} ⭐</p>
        <p>Pemilik: {{ $shop->owner->username }}</p>
        <p>jam operasional: {{ $shop->open_hour }} - {{ $shop->close_hour }}</p>
    </div>

    <br><br>

    @if($products->count() > 0)
    <div>
        @foreach($products as $product)
        <div>
            @if($product->product_img)
                <img src="{{ asset('storage/' . $product->product_img) }}" alt="{{ $product->product_name }}" width="200">
            @endif
            <h4>{{ $product->product_name }}</h4>
            <p>Game: {{ $product->game->game_name }}</p>
            <p>Kategori: {{ $product->category->category_name }}</p>
            <p><strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong></p>
            <p>Stok: {{ $product->stok }} | Rating: {{ number_format($product->rating, 1) }} ⭐</p>
            <a href="{{ route('products.detail', $product->product_id) }}">Lihat Detail</a>
        </div>
        <br>
        @endforeach
    </div>

    <br>
    {{ $products->links() }}
    @else
    <p>Toko ini belum memiliki produk</p>
    @endif
</div>
@endsection
