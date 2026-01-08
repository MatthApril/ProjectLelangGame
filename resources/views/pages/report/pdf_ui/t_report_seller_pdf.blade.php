<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Seller</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            font-size: 11px; 
        }
        h3 { margin: 10px 0; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th, td { 
            border: 1px solid #000; 
            padding: 8px;
            text-align: center;
        }
        th { 
            background: #eee;
            font-weight: bold;
        }
        .text-left { text-align: left; }
        .header-info {
            margin-bottom: 20px;
        }
        .stats {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .stats table {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<h3 align="center">Laporan Transaksi Toko {{ $shop->shop_name }}</h3>
<p align="center">
    Periode : {{ date('d/m/Y', strtotime($start_date)) }} s/d {{ date('d/m/Y', strtotime($end_date)) }}
</p>
<div class="stats">
    <table>
        <tr>
            <th>Total Transaksi</th>
            <td>{{ $totalTransactions }}</td>
        </tr>
        <tr>
            <th>Total Pendapatan (Selesai)</th>
            <td>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Dalam Proses</th>
            <td>Rp{{ number_format($totalPending, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Dibatalkan</th>
            <td>{{ $totalCancelled }}</td>
        </tr>
    </table>
</div>

<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="12%">Tanggal</th>
            <th width="15%">Order ID</th>
            <th width="15%">Pembeli</th>
            <th width="20%" class="text-left">Produk</th>
            <th width="5%">Qty</th>
            <th width="15%">Subtotal</th>
            <th width="13%">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orderItems as $i => $item)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $item->paid_at ? $item->paid_at->format('d/m/Y H:i') : '-' }}</td>
            <td>{{ $item->order_id }}</td>
            <td>{{ $item->order->account->username }}</td>
            <td class="text-left">{{ $item->product->product_name ?? 'Produk Dihapus' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            <td>
                @if($item->status == 'paid')
                    Dibayar
                @elseif($item->status == 'shipped')
                    Dikirim
                @elseif($item->status == 'completed')
                    Selesai
                @elseif($item->status == 'cancelled')
                    Dibatalkan
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8">Tidak Ada Data Transaksi</td>
        </tr>
        @endforelse
    </tbody>
</table>

<p style="margin-top: 30px;">
    <small>Dicetak pada : {{ date('d/m/Y H:i:s') }}</small>
</p>

</body>
</html>