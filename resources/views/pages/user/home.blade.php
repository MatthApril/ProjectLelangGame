@extends('layouts.template')

@section('title', 'Beranda | LelangGame')

@section('content')
    <div class="container-fluid">
        <section>
            <h6 class="fw-bold">Game Populer</h6>
            <div>
                @foreach ($featuredGames as $game)
                    <div>
                        @if ($game->game_img)
                            <img src="{{ asset('storage/' . $game->game_img) }}" alt="{{ $game->game_name }}" width="200">
                        @endif
                        <p>{{ $game->game_name }}</p>
                        <p>{{ $game->products_count }} Produk</p>
                        <a href="{{ route('games.detail', $game->game_id) }}" class="text-decoration-none link-footer">Lihat Detail</a>
                    </div>
                    <br>
                @endforeach
            </div>
            <a href="{{ route('games.index') }}" class="text-decoration-none link-footer">Lihat Semua Game →</a>
        </section>

        <br>

        <section>
            <h6 class="fw-bold">Produk Terbaru</h6>
            <div>
                @foreach ($latestProducts as $product)
                    <div>
                        @if ($product->product_img)
                            <img src="{{ asset('storage/' . $product->product_img) }}" alt="{{ $product->product_name }}"
                                width="200">
                        @endif
                        <p>{{ $product->product_name }}</p>
                        <p>{{ $product->game->game_name }} - {{ $product->category->category_name }}</p>
                        <p><strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong></p>
                        <p>Stok: {{ $product->stok }} | Rating: {{ number_format($product->rating, 1) }} ⭐</p>
                        <a href="{{ route('products.detail', $product->product_id) }}" class="text-decoration-none link-footer">Lihat Detail</a>
                    </div>
                    <br>
                @endforeach
            </div>
            <a href="{{ route('products.index') }}" class="text-decoration-none link-footer">Lihat Semua Produk →</a>
        </section>

        <br>

        <div class="row row-cols-1 row-cols-md-4 g-4 text-center">
            <div class="col">
                <a href="{{ route('user.topUp') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="img-body">
                            <img src="https://d1x91p7vw3vuq8.cloudfront.net/game_category/202396/hwrs5aeqdh8axc6rqurhrl.svg" class="card-img-top" alt="Top Up Game">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Top Up Game</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('user.joki') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="img-body">
                            <img src="https://d1x91p7vw3vuq8.cloudfront.net/game_category/202516/ob8d56hujbmc54si9uyflf.svg" class="card-img-top" alt="Joki">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Joki</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('user.akun') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="img-body">
                            <img src="https://d1x91p7vw3vuq8.cloudfront.net/game_category/202396/dg5frlof3qo30u8st1gcs.svg" class="card-img-top" alt="Akun">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Akun</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('user.item') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="img-body">
                            <img src="https://d1x91p7vw3vuq8.cloudfront.net/game_category/202396/507fm72v5sn1th89seopsq.svg" class="card-img-top" alt="Item">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Item</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <br>

        <section>
            <h6 class="fw-bold">Toko Terpercaya</h6>
            <div>
                @foreach ($topShops as $shop)
                    <div>
                        @if ($shop->shop_img)
                            <img src="{{ asset('storage/' . $shop->shop_img) }}" alt="{{ $shop->shop_name }}"
                                width="100">
                        @endif
                        <p>{{ $shop->shop_name }}</p>
                        <p>Rating: {{ number_format($shop->shop_rating, 1) }} ⭐</p>
                        <a href="{{ route('shops.detail', $shop->shop_id) }}" class="text-decoration-none link-footer">Kunjungi Toko</a>
                    </div>
                    <br>
                @endforeach
            </div>
        </section>

        <br>

        <div class="w-100">
            <h6 class="fw-bold">Owners</h6>
            <table border="1" class="table table-striped">
                <tr>
                    <th>No</th>
                    <th>Owner</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
                @foreach ($owners as $index => $owner)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $owner->username }}</td>
                        <td>{{ $owner->email }}</td>
                        <td>
                            <a href="{{ route('user.chat.show', ['userId' => $owner->user_id]) }}">
                                <button class="btn btn-primary">
                                    Chat
                                </button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
