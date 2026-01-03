@extends('layouts.template')

@section('title', 'Daftar Pesanan Seller | LelangGame')

@section('content')

    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pesanan Masuk</li>
        </ol>
    </nav>
    <h2>Daftar Pesanan</h2>
    <hr>

     @if(session('success'))
        <div style="padding: 10px; background: green; color: white; margin: 10px 0;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding: 10px; background: red; color: white; margin: 10px 0;">
            {{ session('error') }}
        </div>
    @endif

    <form method="GET" style="margin-bottom: 20px;">
        <label><strong>Filter Status:</strong></label>
        <select name="status" onchange="this.form.submit()" style="padding: 5px;">
            <option value="">Semua Status</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid (Menunggu Dikirim)</option>
            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped (Dalam Pengiriman)</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
        </select>
    </form>

    <table class="table">
        <tr>
            <th>No</th>
            <th>Order ID</th>
            <th>Produk</th>
            <th>Buyer</th>
            <th>Qty</th>
            <th>Subtotal</th>
            <th>Status</th>
            <th>Tanggal Pesanan</th>
            <th>Aksi</th>
        </tr>
        @forelse ($orders as $item)
            <tr>
                <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                <td>#{{ $item->order->order_id }}</td>
                <td>{{ $item->product->product_name }}</td>
                <td>{{ $item->order->account->username }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->paid_at->format('d M Y H:i') }}</td>
                <td>
                    @if($item->status === 'paid')
                        <form action="{{ route('seller.orders.ship', $item->order_item_id) }}" method="POST" style="display:inline; margin-right: 5px;">
                            @csrf
                            <button type="submit" onclick="return confirm('Kirim pesanan ini?')" style="padding: 5px 10px; background: blue; color: white; border: none; cursor: pointer;">
                                Kirim
                            </button>
                        </form>
                        <form action="{{ route('seller.orders.cancel', $item->order_item_id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" onclick="return confirm('Batalkan pesanan? Saldo buyer akan dikembalikan.')" style="padding: 5px 10px; background: red; color: white; border: none; cursor: pointer;">
                                Cancel
                            </button>
                        </form>
                    @elseif($item->status === 'shipped')
                        <div>
                            <small style="color: gray;">Menunggu konfirmasi buyer</small><br>
                            <small style="color: blue;">Auto-complete: {{ $item->shipped_at->addDays(3)->format('d M Y') }}</small>
                        </div>
                    @elseif($item->status === 'completed')
                        <span style="color: green;">Pesanan selesai</span>
                    @else
                        <span style="color: red;">Pesanan dibatalkan</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center;">Tidak ada pesanan</td>
            </tr>

        @endforelse
    </table>

    <div style="margin-top: 20px;">
        {{ $orders->links() }}
    </div>

@endsection
