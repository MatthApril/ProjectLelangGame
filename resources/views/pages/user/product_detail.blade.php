@extends('layouts.template')

@section('title', 'Detail Produk | LelangGame')

@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="container">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb mt-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('user.home') }}">Beranda</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index') }}">Semua Produk</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Detail Produk
                </li>
            </ol>
        </nav>

        <h2 class="fw-semibold">{{ $product->product_name }}</h2>

        <p class="text-secondary">
            <i class="bi bi-grid"></i> {{ $product->category->category_name }} |
            <i class="bi bi-controller"></i> {{ $product->game->game_name }}
        </p>

        <hr>

        <div class="row">
            <div class="col-md-8 my-3">
                <div class="card p-3">
                    <div class="row">

                        <div class="col-md-5 text-center my-2">
                            @if ($product->product_img)
                                <img src="{{ asset('storage/' . $product->product_img) }}"
                                    alt="{{ $product->product_name }}" width="300" class="rounded shadow">
                            @endif
                        </div>

                        <div class="col-md-7 my-2">

                            <div class="d-flex align-items-center gap-1">
                                <h4 class="fw-bold text-primary mb-0">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </h4>
                                <p class="text-secondary mb-0">
                                    / {{ $product->category->category_name }}
                                </p>
                            </div>

                            <hr>

                            <div class="row d-flex align-items-center">

                                <div class="col-6">
                                    <div class="d-flex align-items-center gap-1">

                                        <div>
                                            @if ($product->shop->shop_img)
                                                <img src="{{ asset('storage/' . $product->shop->shop_img) }}"
                                                    alt="Foto Toko" width="50" class="rounded-5">
                                            @else
                                                <i class="bi bi-person-circle fs-1"></i>
                                            @endif
                                        </div>

                                        <div>
                                            <p class="m-0 fw-semibold">
                                                {{ $product->shop->shop_name }}
                                            </p>
                                            <p class="m-0">
                                                <i class="bi bi-star-fill text-warning"></i>
                                                {{ number_format($product->rating, 1) }} / 5.0
                                            </p>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-6 text-end">
                                    <a href="{{ route('shops.detail', $product->shop->shop_id) }}"
                                        class="text-decoration-none fw-semibold">
                                        Kunjungi <br> Toko
                                    </a>
                                </div>

                            </div>

                            <hr>

                        </div>
                    </div>
                </div>
                <h4 class="fw-semibold mt-3">Deskripsi Produk</h4>
                <hr>
                <div class="card p-3">
                    <p>{{ $product->description }}</p>
                </div>
            </div>
            <div class="col-md-4 my-3">
                @auth
                @error('quantity')
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @enderror
                <div class="card p-3">
                    <h5 class="fw-semibold">Informasi Pesanan</h5>
                    <hr>
                    <p class="m-0 fw-semibold">Stok : {{ $product->stok }}</p>
                    <input type="number" class="form-control" min="1" max="{{ $product->stok }}" value="1"
                    id="quantity" name="quantity" required>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold">Subtotal :</h5>
                        <h5 class="text-primary fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</h5>
                    </div>
                    <div class="alert alert-light my-2" role="alert">
                        <i class="bi bi-info-circle-fill text-primary"></i> Wajib Update Info Login Akun Setelah
                        Melakukan Pembelian!
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <form class="flex-grow-1" action="{{ route('chat.open', $product->shop->owner->user_id) }}" method="GET">
                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                            <input type="hidden" name="return_url" value="{{ route('products.detail', $product->product_id) }}">
                            <button type="submit" class="btn btn-outline-primary text-center w-100">
                                <i class="bi bi-chat-left"></i>
                            </button>
                        </form>
                        <form class="flex-grow-1" action="{{ route('user.cart.add', $product->product_id) }}" method="POST">
                            @csrf
                                <button type="submit" class="btn btn-outline-primary text-center w-100  ">
                                    <i class="bi bi-cart3"></i> Tambahkan Ke Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="d-grid">
                        <p class="text-secondary">Maaf, anda tidak bisa membeli produk karena belum masuk ke dalam akun,
                            silahkan masuk terlebih dahulu!</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-5"><i
                                class="bi bi-box-arrow-in-right"></i> Masuk Untuk Membeli Produk</a>
                    </div>
                @endauth
            </div>
        </div>

        <div class="my-4">
            <h4 class="fw-semibold">
                Review Produk ({{ $product->comments->count() }})
            </h4>
            <hr>

            @if ($product->comments->count() > 0)

                <p>
                    Rating Rata-Rata:
                    <strong>{{ number_format($product->rating, 1) }}/5</strong> ⭐
                </p>

                <div class="mt-3">
                    @foreach ($product->comments as $comment)
                        <div class="card mb-3">
                            <div class="card-body">

                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $comment->user->username }}</strong>
                                        <span class="text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $comment->rating)
                                                    ⭐
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                        </span>
                                    </div>

                                    <small class="text-secondary">
                                        {{ $comment->created_at->format('d M Y') }}
                                    </small>
                                </div>

                                @if ($comment->comment)
                                    <p class="mt-2 mb-0">
                                        {{ $comment->comment }}
                                    </p>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-secondary">
                    Belum ada review untuk produk ini.
                </p>
            @endif
        </div>

        @if ($relatedProducts->count() > 0)
            <div>
                <h4 class="fw-semibold">Produk Terkait</h4>
                <hr>

                @foreach ($relatedProducts as $related)
                    <div class="col-md-3 mt-2">
                        {{-- <a href="{{ route('products.detail', $product->product_id) }}" class="text-decoration-none text-dark"> --}}
                        <div class="card">
                            @if ($related->product_img)
                                <img src="{{ asset('storage/' . $related->product_img) }}"
                                    alt="{{ $related->product_name }}" class="card-img-top" height="170">
                            @endif
                            <div class="card-body">
                                <h5 class="fw-bold">{{ $related->product_name }}</h5>
                                <h5 class="text-primary fw-semibold">Rp{{ number_format($related->price, 0, ',', '.') }}
                                </h5>
                                <p class="text-secondary">
                                    <i class="bi bi-grid"></i> Kategori : {{ $related->category->category_name }} <br>
                                    <i class="bi bi-controller"></i> Game : {{ $related->game->game_name }}
                                </p>
                                <div class="d-flex justify-content-between text-secondary">
                                    <div>
                                        <i class="bi bi-box-seam"></i> Stok {{ $related->stok }}
                                    </div>
                                    <div>
                                        <i class="bi bi-star"></i> Rating {{ number_format($related->rating, 1) }}
                                    </div>
                                </div>
                                <a href="{{ route('products.detail', $related->product_id) }}"
                                    class="btn btn-primary btn-sm float-end mt-2">Lihat Produk <i
                                        class="bi bi-caret-right-fill"></i></a>
                            </div>
                        </div>
                        {{-- </a> --}}
                    </div>
                    {{-- @empty --}}
                    {{-- <h4 class="fw-semibold mt-4 text-center">Tidak Ada Produk Ditemukan</h4> --}}
                    {{-- @endforelse
                {{ $products->links() }} --}}
                    {{-- <div>
                    @if ($related->product_img)
                        <img
                            src="{{ asset('storage/' . $related->product_img) }}"
                            alt="{{ $related->product_name }}"
                            width="150"
                        >
                    @endif

                    <h4>{{ $related->product_name }}</h4>
                    <p>
                        Rp {{ number_format($related->price, 0, ',', '.') }}
                    </p>

                    <a href="{{ route('products.detail', $related->product_id) }}">
                        Lihat Detail
                    </a>
                </div>
                <br> --}}
                @endforeach
            </div>
        @endif

    </div>
@endsection
