@extends('layouts.template')

@section('title', 'Daftar Lelang | LelangGame')

@section('content')

    <h1>Daftar Lelang</h1>

    @foreach ($auctions as $auction)
        <div class="auction-item">
            @if ($auction->product && $auction->product->shop && $auction->product->shop->owner)
                <p>Produk: {{ $auction->product->product_name ?? 'Produk dihapus' }}</p>
                @if ($auction->product->product_img)
                    <img src="{{ asset('storage/' . $auction->product->product_img) }}"
                        alt="{{ $auction->product->product_name }}" width="200" class="img-fluid">
                @endif
                @if ($auction->highestBid)
                    <p>Pemenang: {{ $auction->highestBid->user->username }}</p>
                @else
                    <em>Tidak ada pemenang</em>
                @endif
                <p>Toko: {{ $auction->product->shop->shop_name }}</p>
                <p>Pemilik Toko: {{ $auction->product->shop->owner->username }}</p>
                <p>Harga Terkini: Rp {{ number_format($auction->current_price ?? 0, 0, ',', '.') }}</p>
                <p>Waktu Selesai: {{ optional($auction->end_time)->format('d M Y H:i') ?? '-' }}</p>
                @if (now()->greaterThanOrEqualTo($auction->start_time))
                    <p>Status: Sedang Berlangsung</p>
                    <form action="{{ route('user.auction.detail', $auction->auction_id) }}" method="get">
                        <button class="btn btn-primary" type="submit">Lihat Detail & Pasang Tawaran</button>
                    </form>
                @else
                    <p>Status: Akan Dimulai</p>
                @endif
            @else
                <p>Informasi lelang tidak tersedia karena produk atau toko telah dihapus.</p>
            @endif
        </div>
    @endforeach

@endsection
