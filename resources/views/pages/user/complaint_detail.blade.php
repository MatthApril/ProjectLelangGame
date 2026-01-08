@extends('layouts.template')

@section('title', 'Detail Komplain | LelangGame')

@section('content')
    <div class="container">
        <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb mt-3">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.complaints.index') }}">Komplain</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Komplain</li>
            </ol>
        </nav>
        <h2 class="fw-semibold">Detail Komplain #{{ $complaint->complaint_id }}</h2>
        <hr>

        <div class="card p-3">
            <div class="row">
                <div class="col-md-5 text-center my-2">
                    @if ($complaint->orderItem->product->product_img)
                        <img src="{{ asset('storage/' . $complaint->orderItem->product->product_img) }}" alt=""
                            class="img-fluid rounded shadow" style="width: 100%">
                    @endif
                </div>

                <div class="col-md-7 my-2">
                    <h4 class="fw-semibold">{{ $complaint->orderItem->product->product_name }}</h4>
                    <p class="text-secondary">
                        <i class="bi bi-grid"></i> {{ $complaint->orderItem->product->category->category_name }} |
                        <i class="bi bi-controller"></i> {{ $complaint->orderItem->product->game->game_name }}
                    </p>
                    <hr>
                    <p class="m-0">Jumlah : <span class="fw-semibold">{{ $complaint->orderItem->quantity }}</span></p>
                    <p class="m-0">Subtotal : <span class="fw-semibold">{{ $complaint->orderItem->subtotal }}</span></p>
                    {{-- <div class="d-flex align-items-center gap-1">
                                <h4 class="fw-bold text-primary mb-0">
                                    Rp {{ number_format($complaint->orderItem->product->price, 0, ',', '.') }}
                                </h4>
                                <p class="text-secondary mb-0">
                                    / {{ $complaint->orderItem->product->category->category_name }}
                                </p>
                            </div> --}}

                    <hr>

                    <div class="row d-flex align-items-center">

                        <div class="col-8">
                            <div class="d-flex align-items-center gap-2">

                                <div>
                                    @if ($complaint->orderItem->product->shop->shop_img)
                                        <img src="{{ asset('storage/' . $complaint->orderItem->product->shop->shop_img) }}"
                                            alt="" class="shop-avatar">
                                    @else
                                        <i class="bi bi-person-circle fs-1"></i>
                                    @endif
                                </div>

                                <div>
                                    <p class="m-0 fw-semibold">
                                        {{ $complaint->orderItem->product->shop->shop_name }}
                                    </p>
                                    <p class="m-0">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        {{ number_format($complaint->orderItem->product->rating, 1) }} / 5.0
                                    </p>
                                </div>

                            </div>
                        </div>

                        <div class="col-4 text-end">
                            <a href="{{ route('shops.detail', $complaint->orderItem->product->shop->shop_id) }}"
                                class="text-decoration-none fw-semibold">
                                Kunjungi <br> Toko
                            </a>
                        </div>

                    </div>

                    <hr>
                    <form action="{{ route('chat.open', $complaint->orderItem->product->shop->user_id) }}" method="GET">
                        <button type="submit" class="btn btn-outline-primary text-center">
                            <i class="bi bi-chat"></i> Hubungi Penjual
                        </button>
                    </form>
                </div>
            </div>
            <h4 class="fw-semibold mt-3">Komplain Anda</h4>
            <p class="m-0 text-secondary">{{ $complaint->created_at->format('d M Y H:i') }}</p>
            <hr>
            <label>Deskripsi Masalah : </label>
            <div class="card mb-3">
                <div class="card-body">
                    {{ $complaint->description }}
                </div>
            </div>
            <label>Bukti Foto : </label>
            <div>
                <a href="{{ asset('storage/' . $complaint->proof_img) }}" target="_blank"
                    class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-card-image"></i> Lihat Bukti Saya
                </a>
            </div>
            {{-- <div>
                        <img src="{{ asset('storage/complaints/' . $complaint->proof_img) }}" alt="" onclick="window.open(this.src, '_blank')" class="img-fluid rounded shadow" width="400">
                        <p><i>Klik Gambar Untuk Memperbesar.</i></p>
                    </div> --}}
            @if ($complaint->response)
                <div>
                    <h4 class="fw-semibold mt-5">Tanggapan Seller</h4>
                    <p class="m-0 text-secondary">Tanggal : {{ $complaint->response->created_at->format('d M Y H:i') }}</p>
                    <hr>
                    <label>Pembelaan Seller :</label>
                    <div class="card p-3 mb-3">{{ $complaint->response->message }}</div>

                    @if ($complaint->response->attachment)
                        <label>Bukti Foto :</label>
                        {{-- <div>
                                    <img src="{{ asset('storage/complaints/' . $complaint->response->attachment) }}" alt="" onclick="window.open(this.src, '_blank')" class="img-fluid rounded shadow" width="400">
                                    <p><i>Klik Gambar Untuk Memperbesar.</i></p>
                                </div> --}}
                        <div>
                            <a href="{{ asset('storage/' . $complaint->response->attachment) }}" target="_blank"
                                class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-card-image"></i> Lihat Bukti Seller
                            </a>
                        </div>
                    @endif
                </div>
            @else
                {{-- <div>
                            <p><i class="bi bi-info-circle-fill"></i> Seller belum memberikan tanggapan.</p>
                        </div> --}}
            @endif

            @if ($complaint->status === 'resolved')
                <div class="mt-5">
                    <h4 class="fw-semibold">Keputusan {{ $complaint->is_auto_resolved ? 'Otomatis (Sistem)' : 'Admin' }}
                    </h4>
                    <p class="text-secondary">{{ $complaint->resolved_at->format('d M Y H:i') }}</p>
                    <hr>


                    <div class="card p-3">
                        @if ($complaint->is_auto_resolved)
                            <p class="m-0 fw-semibold">Seller tidak merespons dalam 24 jam</p>
                            <hr>
                        @endif
                        @if ($complaint->decision === 'refund')
                            <p class="m-0 fw-semibold">
                                <i class="bi bi-check-lg text-success"></i>
                                Komplain Disetujui – Refund Berhasil Diproses
                            </p>

                            <p class="text-secondary m-0">
                                Saldo Anda telah bertambah sebesar
                                <strong>Rp {{ number_format($complaint->orderItem->subtotal, 0, ',', '.') }}</strong>.
                            </p>

                            @if ($complaint->is_auto_resolved)
                                <p class="text-secondary m-0 mt-1">
                                    <i class="bi bi-cash-coin"></i>
                                    Refund diberikan secara otomatis karena seller tidak memberikan respons dalam batas
                                    waktu yang ditentukan.
                                </p>
                            @endif
                        @else
                            <p class="m-0 fw-semibold">
                                <i class="bi bi-x-lg text-danger"></i>
                                Komplain Tidak Disetujui
                            </p>

                            <p class="text-secondary m-0">
                                Setelah meninjau bukti dan keterangan dari kedua belah pihak, admin memutuskan bahwa
                                komplain ini
                                <strong>tidak dapat disetujui</strong>.
                            </p>
                        @endif
                    </div>
                </div>
            @else
                <hr>
                <div>
                    <strong><i class="bi bi-info-circle-fill"></i> Status:</strong>
                    @if ($complaint->status === 'waiting_seller')
                        Komplain telah dikirim dan saat ini <strong>menunggu tanggapan dari seller</strong>.
                        Seller memiliki waktu maksimal <strong>3 × 24 jam</strong> untuk memberikan respons.
                    @elseif ($complaint->status === 'waiting_admin')
                        Tanggapan seller telah diterima dan komplain sedang <strong>ditinjau oleh admin</strong>.
                        Admin akan memberikan keputusan akhir dalam waktu maksimal <strong>3 × 24 jam</strong>.
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
