@extends('layouts.template')

@section('title', 'Komplain Pelanggan | LelangGame')

@section('content')
<div class="container">
    <h2>Komplain Pelanggan - {{ $shop->shop_name }}</h2>
    <hr>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div>{{ session('error') }}</div>
    @endif

    @if($complaints->count() > 0)
        <table>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Buyer</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
            @foreach($complaints as $complaint)
            <tr>
                <td>{{ ($complaints->currentPage() - 1) * $complaints->perPage() + $loop->iteration }}</td>
                <td>
                    @if($complaint->orderItem->product->product_img)
                        <img src="{{ asset('storage/' . $complaint->orderItem->product->product_img) }}"  width="50" alt="" class="img-fluid">
                    @endif
                    {{ $complaint->orderItem->product->product_name }}
                </td>
                <td>{{ $complaint->buyer->username }}</td>
                <td>
                    @if($complaint->status === 'waiting_seller')
                        Perlu Tanggapan
                    @elseif($complaint->status === 'waiting_admin')
                        Menunggu Admin
                    @else
                        Selesai
                    @endif
                </td>
                <td>{{ $complaint->created_at->format('d M Y H:i') }}</td>
                <td>
                    <a href="{{ route('seller.complaints.show', $complaint->complaint_id) }}">
                        @if($complaint->status === 'waiting_seller')
                            Lihat & Tanggapi
                        @else
                            Detail
                        @endif
                    </a>
                </td>
            </tr>
            @endforeach
        </table>
        <div class="mt-3">
            {{ $complaints->links() }}
        </div>
    @else
        <div>
            <p>Tidak ada komplain masuk</p>
        </div>
    @endif
</div>
@endsection