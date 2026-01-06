@extends('layouts.template')

@section('title', 'Daftar Pesanan Seller | LelangGame')

@section('content')
<div class="container">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
            <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard Seller</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pesanan</li>
        </ol>
    </nav>
    <h2 class="fw-semibold">Daftar Pesanan</h2>
    <hr>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    <h5 class="fw-semibold">Filter Pesanan Toko Anda</h5>
    <form method="GET" action="{{ route('seller.incoming_orders.index') }}">
        <select name="status" onchange="this.form.submit()" class="form-select">
            <option value="">Semua Status</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid (Menunggu Dikirim)</option>
            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped (Dalam Pengiriman)</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
        </select>
    </form>
    <hr>
    @forelse ($orders as $item)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="fw-semibold">ID Pesanan : #{{ $item->order->order_id }}</h5>
                <span>Tanggal Pesanan : {{ $item->paid_at->format('d M Y H:i') }}</span>
                <hr>
                @if ($item->product->product_img)
                    <img src="{{ asset('storage/' . $item->product->product_img) }}"
                    alt="" class="img-fluid rounded shadow mb-3" width="300">
                @endif
                <span>Produk : {{ $item->product->product_name }}</span>
                <span>Pembeli : {{ $item->order->account->username }}</span>
                <span>Jumlah : {{ $item->quantity }}</span>
                <span>Subtotal : Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                <span>Status : 
                    @if($item->status === 'paid')
                        <span class="text-primary">Paid (Menunggu Dikirim)</span>
                    @elseif($item->status === 'shipped')
                        <span class="text-warning">Shipped (Dalam Pengiriman)</span>
                    @elseif($item->status === 'completed')
                        <span class="text-success">Completed (Selesai)</span>
                    @else
                        <span class="text-danger">Cancelled (Dibatalkan)</span>
                    @endif
                </span>
                <hr>
                <div>
                    @if($item->status === 'paid')
                        <form action="{{ route('seller.orders.ship', $item->order_item_id) }}" method="POST" style="display:inline; margin-right: 5px;">
                            @csrf
                            <button type="submit" onclick="return confirm('Kirim pesanan ini?')" class="btn btn-primary">
                                <i class="bi bi-box-seam"></i> Kirim
                            </button>
                        </form>
                        <form action="{{ route('seller.orders.cancel', $item->order_item_id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" onclick="return confirm('Batalkan pesanan? Saldo pembeli akan dikembalikan.')" class="btn btn-danger">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                        </form>
                    @elseif($item->status === 'shipped')
                        <div>
                            <small class="text-muted">Menunggu konfirmasi pembeli</small><br>
                            <small class="text-secondary">Batas konfirmasi : {{ $item->shipped_at->addDays(3)->format('d M Y') }}</small>
                        </div>
                    @elseif($item->status === 'completed')
                        <span class="text-success">Pesanan selesai</span>
                    @else
                        <span class="text-danger">Pesanan dibatalkan</span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-center my-5">
            <img src="{{ asset('images/order-empty.png') }}" alt="Order Empty" width="300" class="img-fluid mb-3">
            <h5 class="fw-semibold">Belum ada pesanan {{ request('status') ? 'dengan status ini' : '' }}</h5>
            <p class="text-secondary">
                @if(request('status'))
                    Coba ubah filter atau lihat semua pesanan.
                @else
                    Pesanan dari pembeli akan muncul di sini.
                @endif
            </p>
        </div>
     @endforelse
    {{-- <table class="table">
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
                            <button type="submit" onclick="return confirm('Batalkan pesanan? Saldo pembeli akan dikembalikan.')" style="padding: 5px 10px; background: red; color: white; border: none; cursor: pointer;">
                                Cancel
                            </button>
                        </form>
                    @elseif($item->status === 'shipped')
                        <div>
                            <small style="color: gray;">Menunggu konfirmasi pembeli</small><br>
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
    </table> --}}
    <div class="mt-3">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>
@endsection
