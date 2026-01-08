<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Top Seller</title>
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

<h3 align="center">Laporan Top {{ $topLimit }} Seller Dengan Pendapatan Tertinggi</h3>
<p align="center" style="font-size: 10px;">
    Periode : {{ date('d/m/Y', strtotime($start_date)) }} s/d {{ date('d/m/Y', strtotime($end_date)) }}
</p>

<div class="stats">
    <table>
        <tr>
            <th>Top Seller</th>
            <td>{{ $topLimit }}</td>
        </tr>
        <tr>
            <th>Total Transaksi</th>
            <td>{{ $totalTransactions }}</td>
        </tr>
        <tr>
            <th>Total Pendapatan</th>
            <td>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Admin Fee</th>
            <td>Rp{{ number_format($totalAdminFee, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Pendapatan Bersih</th>
            <td>Rp{{ number_format($totalRevenue - $totalAdminFee, 0, ',', '.') }}</td>
        </tr>
    </table>
</div>

<table>
    <thead>
        <tr>
            <th width="5%">Rank</th>
            <th width="20%">Nama Toko</th>
            <th width="15%">Pemilik</th>
            <th width="10%">Transaksi</th>
            <th width="17%">Total</th>
            <th width="17%">Admin Fee</th>
            <th width="16%">Bersih</th>
        </tr>
    </thead>
    <tbody>
        @forelse($topSellers as $i => $shop)
            <tr>
                <td>#{{ $i + 1 }}</td>
                <td>{{ $shop->shop_name }}</td>
                <td>{{ $shop->owner->username }}</td>
                <td>{{ $shop->total_transactions }}</td>
                <td>Rp {{ number_format($shop->total_revenue, 0, ',', '.') }}</td>
                <td class="text-info">Rp {{ number_format($shop->admin_fee, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($shop->net_income, 0, ',', '.') }}</td>
            </tr>
        @empty
        <tr>
            <td colspan="7">Tidak Ada Data Seller</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Total :</th>
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