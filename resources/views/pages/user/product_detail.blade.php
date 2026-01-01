@extends('layouts.template')

@section('content')
    <div class="containe">
        <a href="{{ route('products.index') }}">← Kembali ke Daftar Produk</a>

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
            <p><a href="{{ route('shops.detail', $product->shop->shop_id) }}">{{ $product->shop->shop_name }}</a></p>

            @auth
                <form action="{{ route('user.cart.add', $product->product_id) }}" method="POST">
                    @csrf
                    <label>Jumlah:</label>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stok }}">
                    <button type="submit">Tambah ke Keranjang</button>
                    @error('quantity')
                        <p>{{ $message }}</p>
                    @enderror
                </form>
            @else
                <p><a href="{{ route('login') }}">Login</a> untuk membeli produk ini</p>
            @endauth
        </div>

        @auth
            <form action="{{ route('user.chat.show', $product->shop->owner->user_id) }}" method="GET">
                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                <button type="submit">Chat Penjual</button>
            </form>
        @else
            <p><a href="{{ route('login') }}">Login</a> untuk chat dengan penjual</p>
        @endauth

        <br><br>

        <div class="mt-4">
            <h3>Review Produk ({{ $product->comments->count() }})</h3>

            @if($product->comments->count() > 0)
                <p>Rating Rata-rata: <strong>{{ number_format($product->rating, 1) }}/5</strong> ⭐</p>

                <div class="mt-3">
                    @foreach($product->comments as $comment)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $comment->user->username }}</strong>
                                    <span class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $comment->rating)
                                                ⭐
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </span>
                                </div>
                                <small class="text-muted">{{ $comment->created_at->format('d M Y') }}</small>
                            </div>
                            @if($comment->comment)
                                <p class="mt-2 mb-0">{{ $comment->comment }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">Belum ada review untuk produk ini</p>
            @endif

        </div>
        @if ($relatedProducts->count() > 0)
            <div>
                <h3>Produk Terkait</h3>
                @foreach ($relatedProducts as $related)
                    <div>
                        @if ($related->product_img)
                            <img src="{{ asset('storage/' . $related->product_img) }}" alt="{{ $related->product_name }}"
                                width="150">
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
</div>
@endsection
