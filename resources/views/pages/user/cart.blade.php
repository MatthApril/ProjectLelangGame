@extends('layouts.template')

@section('title', 'Keranjang Belanja | LelangGame')

@section('content')
    <div id="cart-wrapper">
        @include('pages.user.cart-partial', ['cartItems' => $cartItems])
    </div>
    <script>
        $(document).ready(function () {

        function reloadCart() {
            $.get("{{ route('user.cart.partial') }}", function (res) {
                $('#cart-wrapper').html(res.html);
            });
        }

        // BATASI QTY SESUAI STOK (REAL TIME)
        $(document).on('input', '.qty-input', function () {
            let max = parseInt($(this).data('max'));
            let val = parseInt($(this).val());

            if (val > max) {
                $(this).val(max);
            } else if (val < 1 || isNaN(val)) {
                $(this).val(1);
            }
        });

        // UPDATE KE DATABASE
        $(document).on('change', '.qty-input', function () {
            let cartItemId = $(this).data('id');
            let quantity   = $(this).val();

            $.ajax({
                url: "{{ route('user.cart.update') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    cart_item_id: cartItemId,
                    quantity: quantity
                },
                success: function () {
                    reloadCart();
                },
                error: function (err) {
                    alert(err.responseJSON?.message ?? 'Gagal update keranjang');
                }
            });
        });

        });
    </script>
@endsection
