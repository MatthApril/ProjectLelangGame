<!-- filepath: d:\xampp\htdocs\IniProjectBenar\ProjectLelangGame\resources\views\pages\admin\report\income_pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan Platform</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            font-size: 9px; 
        }
        h3 { margin: 10px 0; font-size: 14px; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
            table-layout: fixed;
        }
        th, td { 
            border: 1px solid #000; 
            padding: 5px;
            text-align: center;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        th { 
            background: #eee;
            font-weight: bold;
            font-size: 9px;
        }
        .text-left { 
            text-align: left !important; 
        }
        .stats {
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .stats table {
            width: 100%;
            margin-top: 10px;
        }
        .stats th {
            width: 40%;
        }
    </style>
</head>
<body>

<h3 align="center">Laporan Pendapatan Platform LelangGame</h3>
<p align="center" style="font-size: 10px;">
    Periode: {{ date('d/m/Y', strtotime($start_date)) }} s/d {{ date('d/m/Y', strtotime($end_date)) }}
</p>

<div class="stats">
    <table>
        <tr>
            <th>Total Transaksi</th>
            <td>{{ $totalTransactions }}</td>
        </tr>
        <tr>
            <th>Transaksi Selesai</th>
            <td>{{ $completedOrders }}</td>
        </tr>
        <tr>
            <th>Total Nilai Transaksi (Selesai)</th>
            <td>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Pendapatan Admin Fee</th>
            <td>Rp{{ number_format($totalAdminFee, 0, ',', '.') }}</td>
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
            <th width="3%">No</th>
            <th width="9%">Tanggal</th>
            <th width="11%">Order ID</th>
            <th width="14%" class="text-left">Toko</th>
            <th width="11%">Pembeli</th>
            <th width="19%" class="text-left">Produk</th>
            <th width="5%">Qty</th>
            <th width="14%">Subtotal</th>
            <th width="14%">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orderItems as $i => $item)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $item->paid_at ? $item->paid_at->format('d/m/Y H:i') : '-' }}</td>
            <td>{{ $item->order_id }}</td>
            <td class="text-left">{{ $item->product->shop->shop_name ?? 'Toko Dihapus' }}</td>
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
            <td colspan="9">Tidak Ada Data Transaksi</td>
        </tr>
        @endforelse
    </tbody>
</table>

<p style="margin-top: 20px; font-size: 8px;">
    <small>Dicetak pada: {{ date('d/m/Y H:i:s') }}</small>
</p>

</body>
</html>