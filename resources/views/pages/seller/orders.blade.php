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
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped (Dalam Pengiriman)
                </option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai)
                </option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)
                </option>
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
                        <img src="{{ asset('storage/' . $item->product->product_img) }}" alt=""
                            class="img-fluid rounded shadow mb-3" width="300">
                    @endif
                    <p class="m-0">Produk : {{ $item->product->product_name }}</p>
                    <p class="m-0">Pembeli : {{ $item->order->account->username }}</p>
                    <p class="m-0">Jumlah : {{ $item->quantity }}</p>
                    <p class="m-0">Subtotal : Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    <p class="m-0 fw-semibold">Status :
                        @if ($item->status === 'paid')
                            <span class="text-primary">Paid (Menunggu Dikirim)</span>
                        @elseif($item->status === 'shipped')
                            <span class="text-warning">Shipped (Dalam Pengiriman)</span>
                        @elseif($item->status === 'completed')
                            <span class="text-success">Completed (Selesai)</span>
                        @else
                            <span class="text-danger">Cancelled (Dibatalkan)</span>
                        @endif
                    </p>
                    <form action="{{ route('chat.open', $item->order->account->user_id) }}" method="GET">
                        <button type="submit" class="btn btn-outline-primary btn-sm text-center mt-3">
                            <i class="bi bi-chat"></i> Hubungi Pembeli
                        </button>
                    </form>
                    <hr>
                    <div>
                        @if ($item->status === 'paid')
                            <div class="d-flex gap-2 float-end">
                                <form action="{{ route('seller.orders.cancel', $item->order_item_id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Batalkan pesanan? Saldo pembeli akan dikembalikan.')"
                                        class="btn btn-danger">
                                        <i class="bi bi-x-lg"></i> Batal
                                    </button>
                                </form>
                                <form action="{{ route('seller.orders.ship', $item->order_item_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Apakah yakin mengirim pesanan ini?')"
                                        class="btn btn-success">
                                        <i class="bi bi-check-lg"></i> Kirim
                                    </button>
                                </form>
                            </div>
                        @elseif($item->status === 'shipped')
                            <div>
                                <small class="text-muted">Menunggu konfirmasi pembeli</small><br>
                                <small class="text-secondary">Batas konfirmasi :
                                    {{ $item->shipped_at->addDays(3)->format('d M Y') }}</small>
                            </div>
                        @elseif($item->status === 'completed')
                            <span class="text-success fw-semibold">Pesanan selesai</span>
                        @else
                            <span class="text-danger fw-semibold">Pesanan dibatalkan</span>
                        @endif
                        <span>Produk : {{ $item->product->product_name }}</span>
                        <span>Pembeli : {{ $item->order->account->username }}</span>
                        <span>Jumlah : {{ $item->quantity }}</span>
                        <span>Subtotal : Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        <span>Status :
                            @if ($item->status === 'paid')
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
                            @if ($item->status === 'paid')
                                <form action="{{ route('seller.orders.ship', $item->order_item_id) }}" method="POST"
                                    style="display:inline; margin-right: 5px;">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Kirim pesanan ini?')"
                                        class="btn btn-primary">
                                        <i class="bi bi-box-seam"></i> Kirim
                                    </button>
                                </form>
                                <form action="{{ route('seller.orders.cancel', $item->order_item_id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Batalkan pesanan? Saldo pembeli akan dikembalikan.')"
                                        class="btn btn-danger">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </button>
                                </form>
                            @elseif($item->status === 'shipped')
                                <div>
                                    <small class="text-muted">Menunggu konfirmasi pembeli</small><br>
                                    <small class="text-secondary">Batas konfirmasi :
                                        {{ $item->shipped_at->addDays(3)->format('d M Y') }}</small>
                                </div>
                            @elseif($item->status === 'completed')
                                <span class="text-success">Pesanan selesai</span>
                            @else
                                <span class="text-danger">Pesanan dibatalkan</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center my-5">
                <img src="{{ asset('images/order-empty.png') }}" alt="Order Empty" width="300" class="img-fluid mb-3">
                <h5 class="fw-semibold">Belum ada pesanan {{ request('status') ? 'dengan status ini' : '' }}</h5>
                <p class="text-secondary">
                    @if (request('status'))
                        Coba ubah filter atau lihat semua pesanan.
                    @else
                        Pesanan dari pembeli akan muncul di sini.
                    @endif
                </p>
            </div>
        @endforelse
        <div class="mt-3">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @endsection
