@extends('layouts.template')

@section('title', 'Daftar Pesanan Seller | LelangGame')

@section('content')

    <h2>Daftar Pesanan</h2>
    <hr>
    <table class="table">
        <tr>
            <th>No</th>
            <th>Nama Pembeli</th>
            <th>Nama Produk</th>
            <th>Tanggal Pesanan</th>
            <th>Status</th>
            <th>Total Harga</th>
            <th>Aksi</th>
        </tr>
        @foreach ($orders as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td>
                    {{ $item->order->account->username ?? 'User dihapus' }}
                </td>

                <td>
                    {{ $item->product->product_name ?? 'Produk dihapus' }}
                </td>

                <td>
                    {{ optional($item->order->created_at)->format('d M Y') ?? '-' }}
                </td>

                <td>
                    {{ ucfirst($item->status) }}
                </td>

                <td>
                    Rp {{ number_format($item->subtotal ?? 0, 0, ',', '.') }}
                </td>

                <td>
                    {{-- aksi --}}
                </td>
            </tr>
        @endforeach
    </table>

@endsection
