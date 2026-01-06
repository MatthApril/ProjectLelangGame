@extends('layouts.templateadmin')

@section('title', 'Kelola Komplain')

@section('content')
<div>
    <h2>Kelola Komplain</h2>
    <hr>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div>{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.complaints.index') }}">
        <label><strong>Filter Status:</strong></label>
        <select name="status" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="waiting_seller" {{ request('status') === 'waiting_seller' ? 'selected' : '' }}>Menunggu Seller</option>
            <option value="waiting_admin" {{ request('status') === 'waiting_admin' ? 'selected' : '' }}>Perlu Ditinjau</option>
            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai</option>
        </select>
    </form>

    @if($complaints->count() > 0)
        <table>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Buyer</th>
                <th>Seller</th>
                <th>Status</th>
                <th>Keputusan</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
            @foreach($complaints as $complaint)
                <tr>
                    <td>{{ ($complaints->currentPage() - 1) * $complaints->perPage() + $loop->iteration }}</td>
                    <td>
                        @if($complaint->orderItem->product->product_img)
                            <img src="{{ asset('storage/' . $complaint->orderItem->product->product_img) }}" width="50" alt="">
                        @endif
                        {{ $complaint->orderItem->product->product_name }}
                    </td>
                    <td>{{ $complaint->buyer->username }}</td>
                    <td>{{ $complaint->seller->username }}</td>
                    <td>
                        @if($complaint->status === 'waiting_seller')
                            Waiting Seller
                        @elseif($complaint->status === 'waiting_admin')
                            PERLU REVIEW
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
                        <a href="{{ route('admin.complaints.show', $complaint->complaint_id) }}">
                            @if($complaint->status === 'waiting_admin')
                                Tinjau & Putuskan
                            @else
                                Detail
                            @endif
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>

        <div>
            {{ $complaints->appends(request()->query())->links() }}
        </div>
    @else
        <div>
            <p>
                @if(request('status'))
                    Tidak ada komplain dengan status "{{ request('status') }}"
                @else
                    Tidak ada komplain
                @endif
            </p>
        </div>
    @endif
</div>
@endsection
