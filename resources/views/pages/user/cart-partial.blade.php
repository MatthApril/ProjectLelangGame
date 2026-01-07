<div class="container">
    <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item">
                <a href="{{ route('user.home') }}">Beranda</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Keranjang
            </li>
        </ol>
    </nav>

    {{-- <div class="row"> --}}
    <h2 class="fw-semibold">Keranjang Belanja</h2>
    <hr>
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (count($cartItems) <= 0)
        <div class="text-center mt-5">
            <div>
                <img src="{{ asset('images/cart-empty.png') }}" alt="Cart Empty" width="300" class="img-fluid my-3">
            </div>
            <div>
                <h5 class="fw-semibold">Wah keranjang kamu masih kosong.</h5>
                <p>Mau beli apa hari ini? Masukin keranjang aja dulu daripada nanti lupa.</p>
            </div>
        </div>
    @else
        {{-- <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="checkDefault">
                    <label class="form-check-label" for="checkDefault">
                        Semua Produk
                    </label>
                </div>
                <div>
                    <a href="#" class="text-decoration-none fw-semibold">Hapus Dari Keranjang</a>
                </div>
            </div>
            <hr> --}}

        <div class="row">
            <div class="col-md-8 my-3">
                @foreach ($cartItems as $item)
                    {{-- <a href="{{ route('products.detail', $item->product->product_id) }}" class="text-decoration-none text-dark"> --}}
                    <div class="d-flex align-items-center gap-3">
                        {{-- <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="checkDefault">
                                </div> --}}
                        <div>
                            <img src="{{ asset('storage/' . $item->product->product_img) }}" alt=""
                                width="170" class="img-fluid rounded shadow">
                        </div>

                        <div>
                            <h6 class="fw-bold">{{ $item->product->product_name }}</h6>
                            <p class="text-secondary m-0 mb-2">
                                <i class="bi bi-grid"></i> {{ $item->product->category->category_name }} |
                                <i class="bi bi-controller"></i> {{ $item->product->game->game_name }}
                            </p>
                            <h6 class="fw-bold text-primary m-0">
                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                            </h6>
                            <a href="{{ route('products.detail', $item->product->product_id) }}"
                                class="btn btn-primary btn-sm mt-2">Lihat Produk <i
                                    class="bi bi-caret-right-fill"></i></a>
                        </div>
                    </div>
                    {{-- </a> --}}
                    <div class="d-flex my-3 gap-2">
                        <input type="number" value="{{ $item->quantity }}" min="1"
                            max="{{ $item->product->stok }}" data-max="{{ $item->product->stok }}"
                            data-id="{{ $item->cart_items_id }}" class="form-control qty-input">
                        <form action="{{ route('user.cart.remove', ['cartItemId' => $item->cart_items_id]) }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div>
                    <hr>
                @endforeach
            </div>
            <div class="col-md-4 my-3">
                <div class="card p-3">
                    <h5 class="fw-semibold">Ringkasan Belanja</h5>
                    <hr>
                    <p class="m-0 mb-2">({{ $cartItems->count() }} Produk)</p>
                    @foreach ($cartItems as $item)
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-secondary">{{ $item->product->product_name }}
                                x{{ $item->quantity }}</span>
                            <span class="text-primary">
                                Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="fw-semibold">Total Harga</h6>
                        <h5 class="fw-semibold text-primary">
                            Rp{{ number_format(
                                $cartItems->sum(function ($item) {
                                    return $item->product->price * $item->quantity;
                                }),
                                0,
                                ',',
                                '.',
                            ) }}
                        </h5>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="fw-semibold">Biaya Admin ({{ $admin_fee_percentage }}%)</h6>
                        <h5 class="fw-semibold text-primary">
                            Rp{{ number_format(
                                round(
                                    $cartItems->sum(function ($item) {
                                        return $item->product->price * $item->quantity;
                                    }) *
                                        ($admin_fee_percentage / 100),
                                ),
                                0,
                                ',',
                                '.',
                            ) }}
                        </h5>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="fw-semibold">Total Bayar</h6>
                        <h5 class="fw-semibold text-primary">
                            Rp{{ number_format(
                                $cartItems->sum(function ($item) {
                                    return $item->product->price * $item->quantity;
                                }) +
                                    round(
                                        $cartItems->sum(function ($item) {
                                            return $item->product->price * $item->quantity;
                                        }) *
                                            ($admin_fee_percentage / 100),
                                    ),
                                0,
                                ',',
                                '.',
                            ) }}
                        </h5>
                    </div>
                    <hr>
                    <form action="{{ route('payment.checkout') }}" method="post">
                        @csrf
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary rounded-5 mt-2 mb-1">
                                <i class="bi bi-cart-check"></i> Checkout
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- </div> --}}

            {{-- <tr>
                <td><img src="{{ asset('storage/' . $item->product->product_img) }}" alt="{{ $item->product->product_name }}" width="150px"></td>
                <td>{{ $item->product->product_name }}</td>
                <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                <td><input type="number" value="{{ $item->quantity }}" min="1"
                data-id="{{ $item->cart_items_id }}" class="qty-input" /></td>
                <td>Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                <td>
                <form action="{{ route('user.cart.remove', ['cartItemId' => $item->cart_items_id]) }}"
                method="POST">
                @csrf
                <button type="submit">Hapus</button>
                </form>
                </td>
            </tr> --}}

            {{-- <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr>
                            <td><img src="{{ asset('storage/' . $item->product->product_img) }}"
                                    alt="{{ $item->product->product_name }}" width="150px"></td>
                            <td>{{ $item->product->product_name }}</td>
                            <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                            <td><input type="number" value="{{ $item->quantity }}" min="1"
                                    data-id="{{ $item->cart_items_id }}" class="qty-input" /></td>
                            <td>Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('user.cart.remove', ['cartItemId' => $item->cart_items_id]) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        <p>
            Total :
            Rp
            {{ number_format(
                $cartItems->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                }),
                0,
                ',',
                '.',
            ) }}
        </p>

        <form action="{{ route('payment.checkout') }}" method="post">
            @csrf
            <button type="submit">Checkout</button>
        </form> --}}
    @endif
</div>
</div>
