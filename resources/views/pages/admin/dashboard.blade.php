@extends('layouts.templateadmin')

@section('content')
<div class="my-3">
    <h5 class="fw-semibold text-dark">Dashboard Admin</h5>

    <hr>

    <h6 class="fw-bold">Statistik Sistem</h6>
    <table border="1" class="table table-striped">
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

    {{-- <h6 class="fw-bold">Menu Manajemen</h6>
    <p>
        <a href="{{ route('admin.categories.index') }}" class="text-decoration-none link-footer">Manage Kategori</a> |
        <a href="{{ route('admin.games.index') }}" class="text-decoration-none link-footer">Manage Game</a>
    </p> --}}
</div>
@endsection
