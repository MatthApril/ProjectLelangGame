@extends('layouts.template')

@section('title', 'Komplain | LelangGame')

@section('content')
<div class="container">
        <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb mt-3">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
                <li class="breadcrumb-item active" aria-current="page">Komplain</li>
            </ol>
        </nav>
    <h2 class="fw-semibold">Komplain Saya</h2>
    <hr>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

    @if($complaints->count() > 0)
            @foreach($complaints as $index => $complaint)
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
                            <div class="d-flex align-items-center gap-1">
                                <h4 class="fw-bold text-primary mb-0">
                                    Rp {{ number_format($complaint->orderItem->product->price, 0, ',', '.') }}
                                </h4>
                                <p class="text-secondary mb-0">
                                    / {{ $complaint->orderItem->product->category->category_name }}
                                </p>
                            </div>

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

                        </div>
                    </div>
                    <h4 class="fw-semibold mt-3">Deskripsi Komplain</h4>
                    <p class="m-0 text-secondary">{{ $complaint->created_at->format('d M Y H:i') }}</p>
                    <hr>
                    <div class="card">
                        <div class="card-body">
                            @if($complaint->status === 'waiting_seller')
                                <p class="m-0">Status : <span class="text-primary fw-semibold">Menunggu Seller</span></p>
                            @elseif($complaint->status === 'waiting_admin')
                                <p class="m-0">Status : <span class="text-primary fw-semibold">Menunggu Admin</span></p>
                            @else
                                <p class="m-0">Status : <span class="text-success fw-semibold">Selesai</span></p>
                            @endif
                            @if($complaint->decision === 'refund')
                                <p>Keputusan : <span class="text-success fw-semibold"><i class="bi bi-check-lg"></i> Uang Dikembalikan</span></p>
                            @elseif($complaint->decision === 'reject')
                                <p>Keputusan : <span class="text-danger fw-semibold"><i class="bi bi-x-lg"></i> Komplain Ditolak</span></p>
                            @else
                                <p>Belum Ada Keputusan</p>
                            @endif
                            <a href="{{ route('user.complaints.show', $complaint->complaint_id) }}" class="btn btn-outline-primary btn-sm">
                                Lihat Detail <i class="bi bi-caret-right-fill"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="mt-3">
                {{ $complaints->links() }}
            </div>
    @else
        <div class="text-center mt-5">
            <div>
                <img src="{{ asset('images/complaints-empty.png') }}" alt="Complaints Empty" width="300" class="img-fluid">
            </div>
            <div>
                <h5 class="fw-semibold">Anda belum pernah mengajukan komplain.</h5>
                <p>Silahkan mengajukan komplain jika terjadi masalah pada transaksi Anda.</p>
                        <a href="{{ route('user.orders') }}"
                            class="btn btn-outline-primary rounded rounded-5"><i class="bi bi-cash-coin"></i> Lihat Transaksi</a>
            </div>
        </div>
    @endif
</div>
@endsection