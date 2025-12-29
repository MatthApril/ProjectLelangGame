<a href="{{ route('user.home') }}">Kembali ke Home</a>

<h1>Keranjang Belanja</h1>
<div id="cart">
    @if (count($cartItems) == 0)
        <p>Keranjang belanja Anda kosong.</p>
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
    @endif
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

@if (session('error'))
    <p>{{ session('error') }}</p>
@endif

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif
