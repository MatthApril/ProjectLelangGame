@extends('layouts.template')

@section('content')
<div class="container-fluid mt-2">
    <h1 class="fw-semibold">Dashboard Admin</h1>
    <hr>
    <h3 class="fw-semibold">Statistik Sistem</h3>
    <table border="1" cellpadding="10">
        <tr>
            <td><strong>Total Users</strong></td>
            <td>{{ $totalUsers }}</td>
        </tr>
        <tr>
            <td><strong>Total Sellers</strong></td>
            <td>{{ $totalSellers }}</td>
        </tr>
        <tr>
            <td><strong>Total Toko</strong></td>
            <td>{{ $totalShops }}</td>
        </tr>
        <tr>
            <td><strong>Total Produk</strong></td>
            <td>{{ $totalProducts }}</td>
        </tr>
        <tr>
            <td><strong>Total Pesanan</strong></td>
            <td>{{ $totalOrders }}</td>
        </tr>
        <tr>
            <td><strong>Total Kategori</strong></td>
            <td>{{ $totalCategories }}</td>
        </tr>
        <tr>
            <td><strong>Total Game</strong></td>
            <td>{{ $totalGames }}</td>
        </tr>
    </table>

    <br><br>

    <h2>Menu Manajemen</h2>
    <p>
        <a href="{{ route('admin.categories.index') }}">Manage Kategori</a> |
        <a href="{{ route('admin.games.index') }}">Manage Game</a>
    </p>
</div>
@endsection
