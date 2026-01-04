@extends('layouts.template')

@section('content')
    <div class="container">
        <h1>Detail Pesanan #{{ $order->order_id }}</h1>
        <hr>
        <div id="alert-container"></div>
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
        <div>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($order->total_prices, 0, ',', '.') }}</p>
        </div>
        <h3>Item Pesanan</h3>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <tr style="background: #f0f0f0;">
                <th>Produk</th>
                <th>Toko</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>
                        @if($item->product->product_img)
                            <img src="{{ asset('storage/' . $item->product->product_img) }}" width="50" style="margin-right: 10px;">
                        @endif
                        {{ $item->product->product_name }}
                    </td>
                    <td>{{ $item->shop->shop_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    <td>
                        @if($item->status === 'paid')
                            <span style="background: yellow; padding: 3px 8px; border-radius: 3px;">Menunggu Dikirim</span>
                        @elseif($item->status === 'shipped')
                            <span style="background: lightblue; padding: 3px 8px; border-radius: 3px;">Telah Dikirim</span>
                        @elseif($item->status === 'completed')
                            <span style="background: lightgreen; padding: 3px 8px; border-radius: 3px;">Selesai</span>
                        @else
                            <span style="background: lightcoral; padding: 3px 8px; border-radius: 3px;">Dibatalkan</span>
                        @endif

                        @if($item->complaint)
                            <br><strong>Complaint:</strong>
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
                    </td>
                    <td id="review-section-{{ $item->order_item_id }}">
                        @if($item->status === 'shipped')
                            @if(!$item->complaint()->exists())
                            <form action="{{ route('user.orders.confirm', $item->order_item_id) }}" method="POST" style="display:inline-block; margin-right: 10px;">
                                @csrf
                                <button type="submit" onclick="return confirm('Konfirmasi pesanan sudah diterima?')" style="padding: 5px 10px; background: green; color: white; border: none; cursor: pointer;">
                                    Konfirmasi Terima
                                </button>
                            </form>

                            <a href="{{ route('user.complaints.create', $item->order_item_id) }}" style="padding: 5px 10px; background: red; color: white; border: none; cursor: pointer; text-decoration: none; display: inline-block;">
                                Ajukan Komplain
                            </a>
                        @else
                            <span style="color: orange;">Komplain sedang diproses</span>
                        @endif

                        @if($item->shipped_at)
                            <small style="color: gray; display: block; margin-top: 5px;">
                                Auto-confirm: {{ $item->shipped_at->addDays(3)->diffForHumans() }}
                            </small>
                        @endif
                        @elseif($item->status === 'completed')
                            @if(!$item->hasReview())
                                <button onclick="showReviewModal({{ $item->order_item_id }})" style="padding: 5px 10px; background: blue; color: white; border: none; cursor: pointer;">
                                    Beri Review
                                </button>
                            @else
                                <span style="color: green;">Sudah di-review</span>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <!-- Modal Review -->
    <div id="reviewModal"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border: 2px solid black; padding: 20px; z-index: 1000;">
        <h3>Beri Review</h3>
        <form id="reviewForm">
            @csrf
            <input type="hidden" id="orderItemId">

            <div>
                <label><strong>Rating *</strong></label><br>
                <select name="rating" id="rating" required style="width: 100%; padding: 5px;">
                    <option value="">Pilih Rating</option>
                    <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                    <option value="4">⭐⭐⭐⭐ (4)</option>
                    <option value="3">⭐⭐⭐ (3)</option>
                    <option value="2">⭐⭐ (2)</option>
                    <option value="1">⭐ (1)</option>
                </select>
                <span id="error-rating" style="color: red;"></span>
            </div>

            <br>

            <div>
                <label><strong>Komentar</strong></label><br>
                <textarea name="comment" id="comment" rows="4" maxlength="1000" style="width: 100%; padding: 5px;"></textarea>
                <span id="error-comment" style="color: red;"></span>
            </div>

            <br>

            <button type="button" onclick="submitReview()">Kirim</button>
            <button type="button" onclick="closeReviewModal()">Batal</button>
        </form>
    </div>

    <div id="modalOverlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;">
    </div>

    <script>
        function showReviewModal(orderItemId) {
            document.getElementById('orderItemId').value = orderItemId;
            document.getElementById('reviewForm').reset();
            document.getElementById('error-rating').textContent = '';
            document.getElementById('error-comment').textContent = '';
            document.getElementById('reviewModal').style.display = 'block';
            document.getElementById('modalOverlay').style.display = 'block';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').style.display = 'none';
            document.getElementById('modalOverlay').style.display = 'none';
        }

        function submitReview() {
            const orderItemId = document.getElementById('orderItemId').value;
            const form = document.getElementById('reviewForm');
            const formData = new FormData(form);

            document.getElementById('error-rating').textContent = '';
            document.getElementById('error-comment').textContent = '';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(`{{ url('/user/reviews') }}/${orderItemId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeReviewModal();
                        showAlert('success', data.message);

                        const reviewSection = document.getElementById('review-section-' + orderItemId);
                        reviewSection.innerHTML = '<button disabled>Sudah Review</button>';

                        setTimeout(() => location.reload(), 1500);
                    } else {
                        if (data.errors) {
                            if (data.errors.rating) {
                                document.getElementById('error-rating').textContent = data.errors.rating[0];
                            }
                            if (data.errors.comment) {
                                document.getElementById('error-comment').textContent = data.errors.comment[0];
                            }
                        } else {
                            showAlert('danger', data.message);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'Terjadi kesalahan.');
                });
        }

        function showAlert(type, message) {
            const alertContainer = document.getElementById('alert-container');
            const color = type === 'success' ? 'green' : 'red';
            alertContainer.innerHTML =
                `<div style="padding: 10px; background: ${color}; color: white; margin: 10px 0;">${message}</div>`;
            setTimeout(() => alertContainer.innerHTML = '', 5000);
        }
    </script>
@endsection
