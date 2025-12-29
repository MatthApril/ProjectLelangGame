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

    <div id="cart-wrapper">
        @include('pages.user.cart-partial', ['cartItems' => $cartItems])
    </div>

</body>

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

</html>
