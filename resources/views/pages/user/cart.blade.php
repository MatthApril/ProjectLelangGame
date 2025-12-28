<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>

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
                            <td><input type="number" value="{{ $item->quantity }}" min="0"
                                    data-id="{{ $item->cart_items_id }}" class="qty-input" /></td>
                            <td>Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <form action="" method="post">
        @csrf
        <button type="submit">Checkout</button>
    </form>

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

</body>

<script>
    $(document).ready(function() {

        function reloadCart() {
            $.get("{{ route('user.cart.partial') }}", function(res) {
                $(document).html(res.html);
            });
        }

        $(document).on('change', '.qty-input', function() {
            let cartItemId = $(this).data('id');
            let quantity = $(this).val();
            console.log(cartItemId, quantity);

            $.ajax({
                url: "{{ route('user.cart.update') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    cart_item_id: cartItemId,
                    quantity: quantity
                },
                success: function(res) {
                    reloadCart();
                    console.log('Quantity updated');
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });

    })
</script>

</html>
