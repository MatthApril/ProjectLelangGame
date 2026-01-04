@extends('layouts.template')

@section('title', 'Daftar Komplain Saya')

@section('content')
<div>
    <h2>Daftar Komplain Saya</h2>
    <hr>

    @if(session('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif

    @if($complaints->count() > 0)
        <table>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Toko</th>
                <th>Status</th>
                <th>Keputusan</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>

            @foreach($complaints as $index => $complaint)
            <tr>
                <td>{{ ($complaints->currentPage() - 1) * $complaints->perPage() + $loop->iteration }}</td>
                <td>
                    @if($complaint->orderItem->product->product_img)
                        <img src="{{ asset('storage/' . $complaint->orderItem->product->product_img) }}" width="50" alt="{{ $complaint->orderItem->product->product_name }}">
                    @endif
                    {{ $complaint->orderItem->product->product_name }}
                </td>
                <td>{{ $complaint->orderItem->shop->shop_name }}</td>
                <td>
                    @if($complaint->status === 'waiting_seller')
                        Menunggu Seller
                    @elseif($complaint->status === 'waiting_admin')
                        Menunggu Admin
                    @else
                        Selesai
                    @endif
                </td>
                <td>
                    @if($complaint->decision === 'refund')
                        ✓ Refund
                    @elseif($complaint->decision === 'reject')
                        ✗ Ditolak
                    @else
                        -
                    @endif
                </td>
                <td>{{ $complaint->created_at->format('d M Y H:i') }}</td>
                <td>
                    <a href="{{ route('user.complaints.show', $complaint->complaint_id) }}">
                        Detail
                    </a>
                </td>
            </tr>
            @endforeach
        </table>

        <div>
            {{ $complaints->links() }}
        </div>
    @else
        <div>
            <p>Anda belum pernah mengajukan komplain</p>
            <a href="{{ route('user.orders') }}">
                Lihat Pesanan
            </a>
        </div>
    @endif
</div>
@endsection