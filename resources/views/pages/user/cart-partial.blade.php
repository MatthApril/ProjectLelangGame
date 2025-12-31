<div class="container-fluid">
    <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Keranjang</li>
        </ol>
    </nav>
    <div class="row">
        <div id="cart">
            @if (count($cartItems) == 0)
                <div class="text-center">
                    <div>
                        <img src="{{ asset('images/cart-empty.png') }}" alt="Cart Empty" width="300" class="my-3">
                    </div>
                    <div>
                        <h5 class="fw-semibold">Wah Keranjang Kamu Masih Kosong!</h5>
                        <p>Mau Beli Apa Hari Ini? Masukin Keranjang Aja Dulu Daripada Nanti Lupa.</p>
                    </div>
                </div>
            @else
                <table>
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
        </div>

        <p>Total :
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
        </form>
        @endif
        @if (session('error'))
            <p>{{ session('error') }}</p>
        @endif

        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif
    </div>
</div>
