@extends('layouts.template')

@section('content')
<div class="container">
    <h1>Detail Pesanan #{{ $order->order_id }}</h1>

    <div id="alert-container"></div>

    <div>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
        <p><strong>Total:</strong> Rp {{ number_format($order->total_prices, 0, ',', '.') }}</p>
    </div>

    <hr>

    <h2>Item Pesanan</h2>
    <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
                <th>Status</th>
                <th>Review</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->product_name ?? '[Produk Dihapus]' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td>
                        @if($item->status === 'completed')
                            <div id="review-section-{{ $item->order_item_id }}">
                                @if($item->hasReview())
                                    <button disabled>Sudah Review</button>
                                @else
                                    <button onclick="showReviewModal({{ $item->order_item_id }})">Beri Review</button>
                                @endif
                            </div>
                        @else
                            -
                        @endif
                    </td>
                </tr>

                @if($item->status === 'completed' && $item->hasReview())
                    <tr>
                        <td colspan="7" style="background: #f5f5f5;">
                            <strong>Review Anda:</strong><br>
                            Rating: {{ $item->comment->rating }}/5
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $item->comment->rating ? '⭐' : '☆' }}
                            @endfor
                            <br>
                            @if($item->comment->comment)
                                Komentar: {{ $item->comment->comment }}<br>
                            @endif
                            <small>{{ $item->comment->created_at->format('d M Y H:i') }}</small>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <br>
    <a href="{{ route('user.orders') }}">← Kembali</a>
</div>

<!-- Modal Review -->
<div id="reviewModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border: 2px solid black; padding: 20px; z-index: 1000;">
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

<div id="modalOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;"></div>

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
    alertContainer.innerHTML = `<div style="padding: 10px; background: ${color}; color: white; margin: 10px 0;">${message}</div>`;
    setTimeout(() => alertContainer.innerHTML = '', 5000);
}
</script>
@endsection
