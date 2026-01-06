@extends('layouts.template')

@section('title', 'Beranda | LelangGame')

@section('content')
<div class="sampul">
    <div class="container">
        <div class="row mb-4 px-2">
            <div class="col-md-3 mt-4 position-relative">
                <img src="{{ asset('images/sampul/img_sampul_1.jpg') }}" alt="" style="width: 100%;" class="img-fluid rounded shadow-lg">
                <a href="#" class="btn btn-outline-light position-absolute bottom-0 start-50 translate-middle-x mb-5 px-5 text-nowrap">Lihat Semua <i class="bi bi-caret-right-fill"></i></a>
            </div>
            <div class="col-md-3 mt-4 position-relative">
                <img src="{{ asset('images/sampul/img_sampul_2.jpg') }}" alt="" style="width: 100%;" class="img-fluid rounded shadow-lg">
                <a href="#" class="btn btn-outline-light position-absolute bottom-0 start-50 translate-middle-x mb-5 px-5 text-nowrap">Lihat Semua <i class="bi bi-caret-right-fill"></i></a>
            </div>
            <div class="col-md-3 mt-4 position-relative">
                <img src="{{ asset('images/sampul/img_sampul_3.jpg') }}" alt="" style="width: 100%;" class="img-fluid rounded shadow-lg">
                <a href="#" class="btn btn-outline-light position-absolute bottom-0 start-50 translate-middle-x mb-5 px-5 text-nowrap">Lihat Semua <i class="bi bi-caret-right-fill"></i></a>
            </div>
            <div class="col-md-3 mt-4 position-relative">
                <img src="{{ asset('images/sampul/img_sampul_4.jpg') }}" alt="" style="width: 100%;" class="img-fluid rounded shadow-lg">
                <a href="#" class="btn btn-outline-light position-absolute bottom-0 start-50 translate-middle-x mb-5 px-5 text-nowrap">Lihat Semua <i class="bi bi-caret-right-fill"></i></a>
            </div>
        </div>
        <h6 class="m-0 text-white text-center mb-4"><i class="bi bi-shield-check"></i> Transaksi Aman &nbsp;|&nbsp; <i class="bi bi-cash-coin"></i> Garansi Uang Kembali &nbsp;|&nbsp; <i class="bi bi-headset"></i> Bantuan Customer Care</h6>
    </div>
</div>
<div class="container mt-5">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="fw-bold">Produk Terbaru</h2>
        <a href="{{ route('products.index') }}" class="text-decoration-none fw-semibold">Lihat Semua Produk <i class="bi bi-chevron-right"></i></a>
    </div>
        <hr>
        <div class="row">
            @foreach ($latestProducts as $product)
                <div class="col-md-3 mt-3">
                    {{-- <a href="{{ route('products.detail', $product->product_id) }}" class="text-decoration-none text-dark"> --}}
                        <div class="card">
                            @if($product->product_img)
                                <img 
                                    src="{{ asset('storage/' . $product->product_img) }}" 
                                    alt="" 
                                    class="card-img-top product-img-16x9"
                                >
                            @endif
                            <div class="card-body">
                                <h5 class="fw-bold">{{ $product->product_name }}</h5>
                                <h5 class="text-primary fw-semibold">Rp{{ number_format($product->price, 0, ',', '.') }}</h5>
                                <p class="text-secondary">
                                    <i class="bi bi-grid"></i> Kategori : {{ $product->category->category_name }} <br>
                                    <i class="bi bi-controller"></i> Game : {{ $product->game->game_name }}
                                </p>
                                <div class="d-flex justify-content-between text-secondary">
                                    <div>
                                        <i class="bi bi-box-seam"></i> Stok {{ $product->stok }}
                                    </div>
                                    <div>
                                        <i class="bi bi-star"></i> Rating {{ number_format($product->rating, 1) }}
                                    </div>
                            </div>
                            <a href="{{ route('products.detail', $product->product_id) }}" class="btn btn-primary btn-sm float-end mt-3">Lihat Produk <i class="bi bi-caret-right-fill"></i></a>
                            </div>
                        </div>
            {{-- </a> --}}
                </div>
            @endforeach
        </div>
</div>
<div class="container mt-5">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="fw-bold">Produk Lelang</h2>
        <a href="{{ route('products.index') }}" class="text-decoration-none fw-semibold">Semua Produk Lelang <i class="bi bi-chevron-right"></i></a>
    </div>
        <hr>
        <div class="row">
            @foreach ($latestProducts as $product)
                <div class="col-md-3 mt-3">
                    {{-- <a href="{{ route('products.detail', $product->product_id) }}" class="text-decoration-none text-dark"> --}}
                        <div class="card">
                            @if($product->product_img)
                                <img 
                                    src="{{ asset('storage/' . $product->product_img) }}" 
                                    alt="" 
                                    class="card-img-top product-img-16x9"
                                >
                            @endif
                            <div class="card-body">
                                <h5 class="fw-bold">{{ $product->product_name }}</h5>
                                <h5 class="text-primary fw-semibold">Rp{{ number_format($product->price, 0, ',', '.') }}</h5>
                                <p class="text-secondary">
                                    <i class="bi bi-grid"></i> Kategori : {{ $product->category->category_name }} <br>
                                    <i class="bi bi-controller"></i> Game : {{ $product->game->game_name }}
                                </p>
                                <div class="d-flex justify-content-between text-secondary">
                                    <div>
                                        <i class="bi bi-box-seam"></i> Stok {{ $product->stok }}
                                    </div>
                                    <div>
                                        <i class="bi bi-star"></i> Rating {{ number_format($product->rating, 1) }}
                                    </div>
                            </div>
                            <a href="{{ route('products.detail', $product->product_id) }}" class="btn btn-primary btn-sm float-end mt-3">Lihat Produk <i class="bi bi-caret-right-fill"></i></a>
                            </div>
                        </div>
            {{-- </a> --}}
                </div>
            @endforeach
        </div>
</div>
<div class="container mt-5">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="fw-bold">Game Terpopuler</h2>
        <a href="{{ route('games.index') }}" class="text-decoration-none fw-semibold">Lihat Semua Game <i class="bi bi-chevron-right"></i></a>
    </div>
        <hr>
        <div class="row">
            @foreach ($featuredGames as $game)
                <div class="col-md-2 mt-4">
                    <div class="card">
                        @if($game->game_img)
                            <img src="{{ asset('storage/' . $game->game_img) }}" alt="" class="card-img-top">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">{{ $game->game_name }}</h5>
                            <span class="text-secondary">{{ $game->products_count }} Produk Tersedia</span>
                            <a href="{{ route('games.detail', $game->game_id) }}" class="btn btn-primary btn-sm mt-3 float-end">Lihat Produk <i class="bi bi-caret-right-fill"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
</div>
<div class="container mt-5">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="fw-bold">Toko Terpercaya</h2>
        {{-- <a href="{{ route('products.index') }}" class="text-decoration-none fw-semibold">Lihat Semua Produk <i class="bi bi-chevron-right"></i></a> --}}
    </div>
        <hr>
        <div class="row">
            @foreach ($topShops as $shop)
                <div class="col-md-2 mt-4">
                <div class="card">
                        @if($shop->shop_img)
                            <img src="{{ asset('storage/' . $shop->shop_img) }}" alt="" class="card-img-top card-img-top shop-avatar w-100 h-100">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">{{ $shop->shop_name }}</h5>
                            <p class="text-secondary m-0"><i class="bi bi-people-fill"></i> Pembeli ({{ $shop->total_buyers }})</p>
                            <p class="text-secondary m-0"><i class="bi bi-star"></i> Rating {{ number_format($shop->shop_rating, 1) }}</p>
                            <a href="{{ route('shops.detail', $shop->shop_id) }}" class="btn btn-primary btn-sm mt-3 float-end">Kunjungi Toko <i class="bi bi-caret-right-fill"></i></a>
                        </div>
                </div>
                {{-- @if ($shop->shop_img)
                    <img src="{{ asset('storage/' . $shop->shop_img) }}" alt="{{ $shop->shop_name }}"
                                width="100">
                @endif
                    <p>{{ $shop->shop_name }}</p>
                    <p>Rating: {{ number_format($shop->shop_rating, 1) }} ⭐</p>
                    <a href="{{ route('shops.detail', $shop->shop_id) }}"
                    class="text-decoration-none link-footer">Kunjungi Toko</a> --}}
                </div>
            @endforeach
        </div>
</div>
<div class="container mt-5">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="fw-bold">Semua Kategori</h2>
        {{-- <a href="{{ route('products.index') }}" class="text-decoration-none fw-semibold">Lihat Semua Produk <i class="bi bi-chevron-right"></i></a> --}}
    </div>
        <hr>
        <div class="row">
            @foreach ($categories as $category)
            <div class="col-md-2 mt-4">
                {{-- <a href="{{ route('products.index', ['category_id' => $category->category_id]) }}" class="text-decoration-none"> --}}
                    <div class="card">
                        {{-- @if($category->category_img)
                            <img src="{{ asset('storage/' . $category->category_img) }}" alt="{{ $category->category_name }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="bi bi-grid-3x3-gap fs-1 text-secondary"></i>
                            </div>
                        @endif --}}
                        <div class="card-body">
                            <h6 class="card-title fw-semibold">{{ $category->category_name }}</h6>
                            <small class="text-secondary">{{ $category->products_count }} Produk</small>
                            <a href="{{ route('products.index', ['category_id' => $category->category_id]) }}" class="btn btn-primary btn-sm mt-3 float-end">Semua Produk <i class="bi bi-caret-right-fill"></i></a>
                        </div>
                    </div>
                {{-- </a> --}}
            </div>
        @endforeach
            {{-- @foreach ($topShops as $shop)
                <div class="col-md-2 mt-4">
                <div class="card">
                        @if($shop->shop_img)
                            <img src="{{ asset('storage/' . $shop->shop_img) }}" alt="" class="card-img-top card-img-top shop-avatar w-100 h-100">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">{{ $shop->shop_name }}</h5>
                            <p class="text-secondary m-0"><i class="bi bi-people-fill"></i> Pembeli ({{ $shop->total_buyers }})</p>
                            <p class="text-secondary m-0"><i class="bi bi-star"></i> Rating {{ number_format($shop->shop_rating, 1) }}</p>
                            <a href="{{ route('shops.detail', $shop->shop_id) }}" class="btn btn-primary btn-sm mt-3 float-end">Kunjungi Toko <i class="bi bi-caret-right-fill"></i></a>
                        </div>
                </div>
                </div>
            @endforeach --}}
        </div>
</div>
    {{-- <div class="container-fluid">
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
                        <a href="{{ route('games.detail', $game->game_id) }}" class="text-decoration-none link-footer">Lihat
                            Detail</a>
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
                        <a href="{{ route('products.detail', $product->product_id) }}"
                            class="text-decoration-none link-footer">Lihat Detail</a>
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
                            <img src="https://d1x91p7vw3vuq8.cloudfront.net/game_category/202396/hwrs5aeqdh8axc6rqurhrl.svg"
                                class="card-img-top" alt="Top Up Game">
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
                            <img src="https://d1x91p7vw3vuq8.cloudfront.net/game_category/202516/ob8d56hujbmc54si9uyflf.svg"
                                class="card-img-top" alt="Joki">
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
                            <img src="https://d1x91p7vw3vuq8.cloudfront.net/game_category/202396/dg5frlof3qo30u8st1gcs.svg"
                                class="card-img-top" alt="Akun">
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
                            <img src="https://d1x91p7vw3vuq8.cloudfront.net/game_category/202396/507fm72v5sn1th89seopsq.svg"
                                class="card-img-top" alt="Item">
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
                        <a href="{{ route('shops.detail', $shop->shop_id) }}"
                            class="text-decoration-none link-footer">Kunjungi Toko</a>
                    </div>
                    <br>
                @endforeach
            </div>
        </section>

        <br>

        <h6>Daftar Lelang</h6>
        @foreach ($auctions as $auction)
            <div>
                @if ($auction->product && $auction->product->shop && $auction->product->shop->owner)
                    <p>Produk: {{ $auction->product->product_name ?? 'Produk dihapus' }}</p>
                    @if ($auction->product->product_img)
                        <img src="{{ asset('storage/' . $auction->product->product_img) }}"
                            alt="{{ $auction->product->product_name }}" width="200">
                    @endif
                    @if ($auction->highestBid)
                        <p>Pemenang: {{ $auction->highestBid->user->username }}</p>
                    @else
                        <em>Tidak ada pemenang</em>
                    @endif
                    <p>Toko: {{ $auction->product->shop->shop_name }}</p>
                    <p>Pemilik Toko: {{ $auction->product->shop->owner->username }}</p>
                    <p>Harga Terkini: Rp {{ number_format($auction->current_price ?? 0, 0, ',', '.') }}</p>
                    <p>Waktu Selesai: {{ optional($auction->end_time)->format('d M Y H:i') ?? '-' }}</p>
                    @if (now()->greaterThanOrEqualTo($auction->start_time))
                        <p>Status: Sedang Berlangsung</p>
                        <form action="{{ route('user.auction.detail', $auction->auction_id) }}" method="get">
                            <button class="btn btn-primary" type="submit">Lihat Detail & Pasang Tawaran</button>
                        </form>
                    @else
                        <p>Status: Akan Dimulai</p>
                    @endif
                @else
                    <p>Informasi lelang tidak tersedia karena produk atau toko telah dihapus.</p>
                @endif
            </div>
            <br>
        @endforeach

        <div>
            <h6>Riwayat Transaksi</h6>
            <a href="{{ route('user.orders') }}">Lihat Semua</a>

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
        </div> --}}
    <div class="container">

    </div>
@endsection
