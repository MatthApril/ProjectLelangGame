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
        .text-info {
            color: #0dcaf0;
            font-weight: bold;
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
            <th>Total Transaksi Selesai</th>
            <td>{{ $totalTransactions }}</td>
        </tr>
        <tr>
            <th>Total Nilai Transaksi</th>
            <td>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Admin Fee</th>
            <td>Rp{{ number_format($totalAdminFee, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Pendapatan Seller</th>
            <td>Rp{{ number_format($totalRevenue - $totalAdminFee, 0, ',', '.') }}</td>
        </tr>
    </table>
</div>

<table>
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="11%">Tanggal</th>
            <th width="14%">Order ID</th>
            <th width="13%">Pembeli</th>
            <th width="8%">Item</th>
            <th width="17%">Total</th>
            <th width="17%">Admin Fee</th>
            <th width="16%">Seller</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $i => $order)
            @php
                $netIncome = $order->total_prices - $order->admin_fee;
            @endphp
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $order->order_date }}</td>
                <td>{{ $order->order_id }}</td>
                <td>{{ $order->account->username }}</td>
                <td>{{ $order->orderItems->count() }}</td>
                <td>Rp {{ number_format($order->total_prices, 0, ',', '.') }}</td>
                <td class="text-info">Rp {{ number_format($order->admin_fee, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($netIncome, 0, ',', '.') }}</td>
            </tr>
        @empty
        <tr>
            <td colspan="8">Tidak Ada Transaksi Selesai</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5">TOTAL:</th>
            <th>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
            <th class="text-info">Rp {{ number_format($totalAdminFee, 0, ',', '.') }}</th>
            <th>Rp {{ number_format($totalRevenue - $totalAdminFee, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

<p style="margin-top: 20px; font-size: 8px;">
    <small>Dicetak pada: {{ date('d/m/Y H:i:s') }}</small>
</p>

</body>
</html>