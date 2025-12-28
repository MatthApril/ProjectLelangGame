@extends('layouts.template')

@section('content')
<div class="px-5 my-3">
    <h5 class="fw-semibold text-dark">Dashboard Admin</h5>

    <hr>

    <h6 class="fw-bold">Statistik Sistem</h6>
    <table border="1" cellpadding="10">
        <tr>
            <td style="border: 1px solid gray; padding: 5px;"><strong>Total Users</strong></td>
            <td style="border: 1px solid gray; padding: 5px;">{{ $totalUsers }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid gray; padding: 5px;"><strong>Total Sellers</strong></td>
            <td style="border: 1px solid gray; padding: 5px;">{{ $totalSellers }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid gray; padding: 5px;"><strong>Total Toko</strong></td>
            <td style="border: 1px solid gray; padding: 5px;">{{ $totalShops }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid gray; padding: 5px;"><strong>Total Produk</strong></td>
            <td style="border: 1px solid gray; padding: 5px;">{{ $totalProducts }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid gray; padding: 5px;"><strong>Total Pesanan</strong></td>
            <td style="border: 1px solid gray; padding: 5px;">{{ $totalOrders }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid gray; padding: 5px;"><strong>Total Kategori</strong></td>
            <td style="border: 1px solid gray; padding: 5px;">{{ $totalCategories }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid gray; padding: 5px;"><strong>Total Game</strong></td>
            <td style="border: 1px solid gray; padding: 5px;">{{ $totalGames }}</td>
        </tr>
    </table>

    <br><br>

    <h6 class="fw-bold">Menu Manajemen</h6>
    <p>
        <a href="{{ route('admin.categories.index') }}" class="text-decoration-none link-footer">Manage Kategori</a> |
        <a href="{{ route('admin.games.index') }}" class="text-decoration-none link-footer">Manage Game</a>
    </p>
</div>
@endsection
