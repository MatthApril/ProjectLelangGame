@extends('layouts.template')

@section('title', 'Pengajuan Komplain | LelangGame')

@section('content')
    <a href="#" id="btnScrollTop" class="btn btn-primary position-fixed rounded-5 btn-lg fw-bold bottom-0 end-0 mb-5 me-3 fs-3" style="z-index: 9999; display: none;"><i class="bi bi-arrow-up"></i></a>
    <div class="container">
        <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb mt-3">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.orders') }}">Transaksi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.orders.detail', $orderItem->order_id) }}">Detail
                        Transaksi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pengajuan Komplain</li>
            </ol>
        </nav>
        <h2 class="fw-semibold">Pengajuan Komplain</h2>
        <hr>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @error('description')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @enderror
        @error('proof_img')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @enderror
        <div>
            <div class="card mb-3">
                <h3 class="fw-semibold p-4 pb-0">Detail Produk</h3>
                <hr>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center my-2">
                            <img src="{{ asset('storage/' . $orderItem->product->product_img) }}" alt=""
                                width="300" class="img-fluid rounded shadow">
                        </div>
                        <div class="col-md-9 my-2">
                            <h4 class="fw-semibold m-0">{{ $orderItem->product->product_name }}</h4>
                            <p class="text-secondary"><i class="bi bi-grid"></i>
                                {{ $orderItem->product->category->category_name }} | <i class="bi bi-controller"></i>
                                {{ $orderItem->product->game->game_name }}</p>
                            <hr>
                            <div class="row d-flex align-items-center">
                                <div class="col-8">
                                    <div class="d-flex align-items-center gap-2">
                                        <div>
                                            @if ($orderItem->product->shop->shop_img)
                                                <img src="{{ asset('storage/' . $orderItem->product->shop->shop_img) }}"
                                                    alt="" class="shop-avatar">
                                            @else
                                                <i class="bi bi-person-circle fs-1"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="m-0 fw-semibold">
                                                {{ $orderItem->product->shop->shop_name }}
                                            </p>
                                            <p class="m-0">
                                                <i class="bi bi-star-fill text-warning"></i>
                                                {{ number_format($orderItem->product->rating, 1) }} / 5.0
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <a href="{{ route('shops.detail', $orderItem->product->shop->shop_id) }}"
                                        class="text-decoration-none fw-semibold">
                                        Kunjungi <br> Toko
                                    </a>
                                </div>
                            </div>
                            <hr>
                            <p class="m-0">Jumlah : <strong>{{ $orderItem->quantity }}</strong></p>
                            <p class="m-0">Harga Satuan :
                                <strong>Rp{{ number_format($orderItem->product_price, 0, ',', '.') }}</strong>
                            </p>
                            <p class="m-0">Subtotal :
                                <strong>Rp{{ number_format($orderItem->subtotal, 0, ',', '.') }}</strong>
                            </p>
                        </div>
                    </div>
                        <div class="card">
                            <h3 class="fw-semibold p-4 pb-0">Form Komplain</h3>
                            <hr>
                            <div class="card-body">
                                <form action="{{ route('user.complaints.store', $orderItem->order_item_id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div>
                                        <label>Deskripsi Masalah *</label>
                                        <textarea name="description" rows="5" maxlength="1000"
                                            placeholder="Jelaskan Masalah Yang Anda Alami Dengan Detail (Minimal 20 Karakter)" class="form-control" required>{{ old('description') }}</textarea>
                                        <p><i>Minimal 20 Karakter, Maksimal 1000 Karakter</i></p>
                                    </div>

                                    <div>
                                        <label>Bukti Foto *</label>
                                        <input type="file" name="proof_img" accept="image/jpeg,image/png,image/jpg"
                                            class="form-control" required>
                                        <p><i>Format: JPG, PNG, JPEG | Maksimal 2MB</i></p>
                                    </div>

                                    <div>
                                        <strong>
                                            <i class="bi bi-exclamation-triangle-fill"></i> Perhatian:
                                        </strong>
                                        <ul>
                                            <li>Setiap produk hanya dapat diajukan <strong>1 kali komplain</strong>.</li>
                                            <li>Harap sertakan <strong>deskripsi masalah</strong> dan <strong>bukti
                                                    pendukung</strong>
                                                yang jelas serta valid.</li>
                                            <li>Seller wajib memberikan respons maksimal dalam waktu <strong>24 jam</strong>
                                                setelah
                                                komplain diajukan.</li>
                                            <li>Admin akan melakukan peninjauan terhadap bukti dari kedua belah pihak dan
                                                <strong>keputusan admin bersifat final</strong>.
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    <a href="{{ route('user.orders.detail', $orderItem->order_id) }}"
                                        class="btn btn-danger">
                                        <i class="bi bi-x-lg"></i> Batal
                                    </a>
                                    <button type="submit"
                                        onclick="return confirm('Pastikan data yang Anda masukkan sudah benar. Ajukan komplain?')"
                                        class="btn btn-success">
                                        Ajukan Komplain <i class="bi bi-caret-right-fill"></i>
                                    </button>
                                </div>
                            </div>
                            </form>
                        </div>
                @endsection
