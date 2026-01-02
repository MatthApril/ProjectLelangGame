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

    <br><br>
        {{-- <h6 class="fw-bold">Menu Manajemen</h6> --}}
        <p>
            <a href="{{ route('admin.categories.index') }}">Manage Kategori</a> |
            <a href="{{ route('admin.games.index') }}">Manage Game</a> |
            <a href="{{ route('admin.notifications.index') }}">Manage Notifikasi</a> |
            <a href="{{ route('admin.comments.index') }}">Manage Comments</a>
        </p>
    </div>
@endsection
