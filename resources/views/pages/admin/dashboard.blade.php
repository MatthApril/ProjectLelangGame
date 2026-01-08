@extends('layouts.templateadmin')

@section('content')
    <div class="container my-3 text-dark">
        <h5 class="fw-semibold text-dark">Dashboard Admin</h5>
        <hr>

        <h4 class="fw-bold">Pengaturan Admin</h4>
        <form action="{{ route('admin.settings.update') }}" method="post">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="platform_fee_percentage" class="form-label">Persentase Biaya Admin (%)</label>
                <input type="number" class="form-control" id="platform_fee_percentage" name="platform_fee_percentage"
                    value="{{ $admin_settings->platform_fee_percentage ?? 0 }}" min="0" max="100" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
        </form>

        <br>

        <h4 class="fw-bold">Statistik Sistem</h4>
        <div class="table-responsive">
            <table border="1" class="table table-bordered">
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
                <tr>
                    <td><strong>Total Notifikasi Terkirim</strong></td>
                    <td>{{ $totalRecipients }}</td>
                </tr>
            </table>
        </div>

        {{-- <h6 class="fw-bold">Menu Manajemen</h6> --}}
        <p>
            {{-- <a href="{{ route('admin.categories.index') }}">Manage Kategori</a> |
            <a href="{{ route('admin.games.index') }}">Manage Game</a> |
            <a href="{{ route('admin.notifications.index') }}">Manage Notifikasi</a> | --}}
            {{-- <a href="{{ route('admin.comments.index') }}">Manage Comments</a> | --}}
            {{-- <a href="{{ route('admin.complaints.index') }}">Manage Complaints</a> --}}
            {{-- <a href="{{ route('admin.cancelled_orders.index') }}">Manage Cancelled Orders</a> --}}
        </p>
    </div>
@endsection
