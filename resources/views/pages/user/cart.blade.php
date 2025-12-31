@extends('layouts.template')

@section('title', 'Keranjang Belanja | LelangGame')

@section('content')
    <div id="cart-wrapper">
        @include('pages.user.cart-partial', ['cartItems' => $cartItems])
    </div>
    <script>
        $(document).ready(function() {

            function reloadCart() {
                $.get("{{ route('user.cart.partial') }}", function(res) {
                    $('#cart-wrapper').html(res.html);
                });
            }

            $(document).on('change', '.qty-input', function() {
                let cartItemId = $(this).data('id');
                let quantity = $(this).val();

                $.ajax({
                    url: "{{ route('user.cart.update') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        cart_item_id: cartItemId,
                        quantity: quantity
                    },
                    success: function() {
                        reloadCart();
                    },
                    error: function(err) {
                        console.error(err.responseJSON);
                    }
                });
            });

        });
    </script>
@endsection
