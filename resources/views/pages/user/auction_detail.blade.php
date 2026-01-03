@extends('layouts.template')

@section('title', 'Lelang User | LelangGame')

@section('content')

    <h1>Detail Lelang</h1>
    <hr>
    <div>
        <h4>{{ $auction->product->product_name ?? 'Produk dihapus' }}</h4>
        @if ($auction->product->product_img)
            <img src="{{ asset('storage/' . $auction->product->product_img) }}" alt="{{ $auction->product->product_name }}"
                width="200">
        @endif
        @if ($auction->highestBid)
            <p>Pemenang: {{ $auction->highestBid->user->username }}</p>
        @else
            <em>Tidak ada pemenang</em>
        @endif
        <p>Toko: {{ $auction->product->shop->shop_name }}</p>
        <p>Harga Awal: Rp {{ number_format($auction->start_price ?? 0, 0, ',', '.') }}</p>
        <p>Harga Terkini: Rp {{ number_format($auction->current_price ?? 0, 0, ',', '.') }}</p>
        <p>Waktu Mulai: {{ optional($auction->start_time)->format('d M Y H:i') ?? '-' }}</p>
        <p>Waktu Selesai: {{ optional($auction->end_time)->format('d M Y H:i') ?? '-' }}</p>
        <p>Status: {{ ucfirst($auction->status) }}</p>
    </div>

    <form action="{{ route('user.auctions.bid', ['auctionId' => $auction->auction_id]) }}" method="post">
        @csrf
        <div class="mb-3">
            <label for="bidAmount" class="form-label">Masukkan Jumlah Tawaran Anda (Rp):</label>
            <input type="number" class="form-control" id="bidAmount" name="bid_price"
                min="{{ $auction->current_price + 1000 }}" placeholder="{{ $auction->current_price + 1000 }}" required>
            <div class="form-text">Tawaran harus lebih tinggi dari harga terkini minimal Rp 1000.</div>
        </div>
        <button type="submit" class="btn btn-primary">Pasang Tawaran</button>
    </form>
    @error('bid_price')
        <div class="text-danger">{{ $message }}</div>
    @enderror

    @if (session('error'))
        <div class="alert alert-danger mt-3" role="alert">
            {{ session('error') }}
        </div>
    @endif

@endsection
