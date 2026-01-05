<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LelangGame</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif;">
    <div style="max-width:600px; margin:40px auto; background:#ffffff; padding:30px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="display:flex; align-items:center; justify-content:center; gap:12px; margin-bottom:25px;">
            <img src="https://res.cloudinary.com/ds4kbzrdd/image/upload/v1766537270/LogoWarna-RemoveBg_atsck7.png" alt="LelangGame Logo" height="56" style="display:block; vertical-align:middle;">
            <h1 style="margin:0; font-size:30px; font-weight:800; line-height:56px; color:#1e293b; vertical-align:middle;">
                LelangGame
            </h1>
        </div>

        <!-- Content -->
        <h4 style="margin:0 0 10px; font-size:18px; color:#334155;">
            Hai User!
        </h4>

        <p style="font-size:15px; color:#475569; line-height:1.6; margin-bottom:20px;">
            Terima kasih telah membeli di <strong>LelangGame</strong>. Berikut detail transaksimu:
        </p>

        <!-- Invoice -->
        {{-- <div style="text-align:center; margin:30px 0;">
            <span style="display:inline-block; background:#2563eb; color:#ffffff; font-size:32px; font-weight:bold; letter-spacing:6px; padding:15px 30px; border-radius:8px;">
                
            </span>
        </div> --}}
        <h5 style="font-size:17px; color:#334155;">Detail Transaksi</h5>
        <table class="table table-bordered">
            <tr>
                <td style="color:#475569;">Order ID</td>
                <td style="color:#334155;">{{ $invoice->order_id }}</td>
            </tr>
            <tr>
                <td style="color:#475569;">Paid At</td>
                <td style="color:#334155;">{{ $invoice->orderItems->first()->paid_at }}</td>
            </tr>
        </table>
        <table class="table table-bordered">
            <tr style="color:#475569;">
                <td>Shop Name</td>
                <td>Product Name</td>
                <td>Product Price</td>
                <td>Quantity</td>
                <td>Subtotal</td>
                <td>Game Name</td>
                <td>Category Name</td>
            </tr>
            @foreach($invoice->orderItems as $item)
                <tr style="color:#334155;">
                    <td>{{ $item->shop->shop_name }}</td>
                    <td>{{ $item->product->product_name }}</td>
                    <td>Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    <td>{{ $item->product->game->game_name }}</td>
                    <td>{{ $item->product->category->category_name }}</td>
                </tr>
            @endforeach
        </table>
        <table class="table table-bordered">
            <tr>
                <td style="color:#475569;">Total Prices</td>
                <td style="color:#334155;">Rp {{ number_format($invoice->total_prices, 0, ',', '.') }}</td>
            </tr>
        </table>
        <p style="font-size:15px; color:#475569; line-height:1.6; margin-bottom:20px;">
            E-mail ini dibuat otomatis, mohon tidak membalas.
        </p>
    </div>
</body>
</html>
