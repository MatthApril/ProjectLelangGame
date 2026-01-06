@extends('layouts.template')

@section('title', 'Detail Transaksi | LelangGame')

@section('content')
    <div class="container">
        <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb mt-3">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.orders') }}">Transaksi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Transaksi</li>
            </ol>
        </nav>
        <h5 class="fw-semibold">Detail Transaksi #{{ $order->order_id }}</h5>
        <hr>
        <div id="alert-container"></div>
        <div>
            <div class="card">
                <div class="card-body">
                    <p class="m-0">Total Harga : <strong>Rp{{ number_format($order->total_prices, 0, ',', '.') }}</strong>
                    </p>
                    <p class="m-0">Tanggal Transaksi : <strong>{{ $order->created_at->format('d-m-Y H:i') }}</strong></p>
                </div>
            </div>

            <h5 class="fw-semibold mt-4">Produk Dalam Transaksi</h5>
            <hr>
            @if ($order->orderItems->isEmpty())
                <div class="text-center">
                    <div>
                        <img src="{{ asset('images/product-empty.png') }}" alt="Product Empty" width="300" class="img-fluid">
                    </div>
                    <div>
                        <h5 class="fw-semibold">Produk tidak ditemukan dalam transaksi ini.</h5>
                        <p>Silahkan hubungi admin untuk informasi lebih lanjut di <i>lelanggameofficial@gmail.com</i>.</p>
                    </div>
                </div>
            @else
                @foreach ($order->orderItems as $index => $item)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center my-2">
                                    <img src="{{ asset('storage/' . $item->product->product_img) }}"
                                        alt="{{ $item->product->product_name }}" width="300" class="img-fluid rounded shadow">
                                </div>
                                <div class="col-md-9 my-2">
                                    <h4 class="fw-semibold m-0">{{ $item->product->product_name }}</h4>
                                    <p class="text-secondary"><i class="bi bi-grid"></i>
                                        {{ $item->product->category->category_name }} | <i class="bi bi-controller"></i>
                                        {{ $item->product->game->game_name }}</p>
                                    <hr>
                                    <div class="row d-flex align-items-center">
                                        <div class="col-8">
                                            <div class="d-flex align-items-center gap-2">
                                                <div>
                                                    @if ($item->product->shop->shop_img)
                                                        <img src="{{ asset('storage/' . $item->product->shop->shop_img) }}"
                                                            alt="" class="shop-avatar">
                                                    @else
                                                        <i class="bi bi-person-circle fs-1"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="m-0 fw-semibold">
                                                        {{ $item->product->shop->shop_name }}
                                                    </p>
                                                    <p class="m-0">
                                                        <i class="bi bi-star-fill text-warning"></i>
                                                        {{ number_format($item->product->rating, 1) }} / 5.0
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <a href="{{ route('shops.detail', $item->product->shop->shop_id) }}"
                                                class="text-decoration-none fw-semibold">
                                                Kunjungi <br> Toko
                                            </a>
                                        </div>
                                    </div>
                                    <hr>
                                    <p class="m-0">Jumlah : <strong>{{ $item->quantity }}</strong></p>
                                    <p class="m-0">Harga Satuan :
                                        <strong>Rp{{ number_format($item->product_price, 0, ',', '.') }}</strong>
                                    </p>
                                    <p class="m-0">Subtotal :
                                        <strong>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                                    </p>
                                    <p class="m-0">
                                        Status :
                                        @if ($item->status === 'paid')
                                            <span class="text-warning fw-semibold">Menunggu
                                                Dikirim</span>
                                        @elseif($item->status === 'shipped')
                                            <span class="text-primary fw-semibold">Telah
                                                Dikirim</span>
                                        @elseif($item->status === 'completed')
                                            <span class="text-success fw-semibold">Selesai</span>
                                        @else
                                            <span class="text-danger fw-semibold">Dibatalkan</span>
                                        @endif

                                        @if ($item->complaint)
                                            <strong>Keluhan :</strong>
                                            @if ($item->complaint->status === 'waiting_seller')
                                                Menunggu Seller
                                            @elseif ($item->complaint->status === 'waiting_admin')
                                                Menunggu Admin
                                            @elseif ($item->complaint->decision === 'refund')
                                                Refund Disetujui
                                            @elseif ($item->complaint->decision === 'reject')
                                                Komplain Ditolak
                                            @endif
                                        @endif
                                    </p>
                                    <div id="review-section-{{ $item->order_item_id }}" class="mt-2">
                                        @if ($item->status === 'shipped')
                                            @if (!$item->complaint()->exists())
                                                <div class="d-flex gap-2 mb-2">
                                                    <form action="{{ route('user.orders.confirm', $item->order_item_id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            onclick="return confirm('Konfirmasi pesanan sudah diterima?')"
                                                            class="btn btn-success btn-sm">
                                                            Konfirmasi Terima
                                                        </button>
                                                    </form>
        
                                                    <a href="{{ route('user.complaints.create', $item->order_item_id) }}"
                                                        class="btn btn-danger btn-sm">
                                                        Ajukan Komplain
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-warning">Komplain sedang diproses</span>
                                            @endif

                                            @if ($item->shipped_at)
                                                <small class="text-secondary d-block mt-1">
                                                    Batas konfirmasi : {{ $item->shipped_at->addDays(3)->diffForHumans() }}
                                                </small>
                                            @endif
                                        @elseif($item->status === 'completed')
                                            @if (!$item->hasReview())
                                                <button type="button" data-bs-toggle="modal"
                                                    data-bs-target="#reviewModal-{{ $item->order_item_id }}"
                                                    class="btn btn-outline-primary btn-sm"><i class="bi bi-star"></i> Beri
                                                    Review</button>
                                            @else
                                                <button disabled class="btn btn-outline-success btn-sm">Sudah Review</button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Review -->
                    <div class="modal fade" id="reviewModal-{{ $item->order_item_id }}" tabindex="-1"
                        aria-labelledby="reviewModalLabel-{{ $item->order_item_id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5 fw-bold" id="reviewModalLabel-{{ $item->order_item_id }}">
                                        Berikan Review Anda!</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form class="reviewForm" data-order-item-id="{{ $item->order_item_id }}">
                                    @csrf
                                    <div class="modal-body">
                                        <label class="mb-2">Rating*</label>
                                        <div class="d-flex gap-2 mb-3">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <input type="radio" class="btn-check"
                                                    name="rating-{{ $item->order_item_id }}"
                                                    id="rating{{ $i }}-{{ $item->order_item_id }}"
                                                    value="{{ $i }}" required>
                                                <label class="btn btn-outline-warning"
                                                    for="rating{{ $i }}-{{ $item->order_item_id }}">
                                                    <i class="bi bi-star-fill"></i> {{ $i }}
                                                </label>
                                            @endfor
                                        </div>
                                        <div class="text-danger mb-2" id="error-rating-{{ $item->order_item_id }}"></div>

                                        <label class="mb-2">Komentar</label>
                                        <textarea name="comment" id="comment-{{ $item->order_item_id }}" rows="4" maxlength="1000"
                                            class="form-control mb-2" placeholder="Berikan Komentar Tentang Produk Disini"></textarea>
                                        <div class="text-danger" id="error-comment-{{ $item->order_item_id }}"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Kirim <i
                                                class="bi bi-caret-right-fill"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.reviewForm').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const orderItemId = this.dataset.orderItemId;
                    const ratingInput = document.querySelector(
                        `input[name="rating-${orderItemId}"]:checked`);
                    const commentInput = document.getElementById(`comment-${orderItemId}`);

                    // Clear previous errors
                    document.getElementById(`error-rating-${orderItemId}`).textContent = '';
                    document.getElementById(`error-comment-${orderItemId}`).textContent = '';

                    // Validasi
                    if (!ratingInput) {
                        document.getElementById(`error-rating-${orderItemId}`).textContent =
                            'Pilih rating terlebih dahulu!';
                        return;
                    }

                    const rating = ratingInput.value;
                    const comment = commentInput.value;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    // Kirim ke route yang benar: user.reviews.store
                    fetch(`/user/reviews/${orderItemId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                rating: rating,
                                comment: comment
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showAlert('success', data.message);
                                const modal = bootstrap.Modal.getInstance(document
                                    .getElementById(`reviewModal-${orderItemId}`));
                                modal.hide();
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                showAlert('danger', data.message || 'Terjadi kesalahan');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('danger', 'Gagal mengirim review. Silakan coba lagi.');
                        });
                });
            });
        });

        function showAlert(type, message) {
            const alertContainer = document.getElementById('alert-container');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill';

            alertContainer.innerHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="bi ${icon}"></i> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }
    </script>
@endsection
