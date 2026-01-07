@extends('layouts.templateadmin')

@section('title', 'Detail Pesanan Dibatalkan')

@section('content')
    <div class="container my-3 text-dark">
        <h5 class="fw-semibold text-dark">Detail Pesanan Dibatalkan</h5>
        <hr>

        @if (session('success'))
            <div>{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div>{{ session('error') }}</div>
        @endif

        <div>
            <strong>Status Refund:</strong>
            @if ($orderItem->is_refunded)
                <span style="color: green; font-weight: bold; font-size: 1.2em;">✓ SUDAH REFUND</span>
            @else
                <span style="color: red; font-weight: bold; font-size: 1.2em;">✗ BELUM REFUND</span>
            @endif
        </div>

        <hr>

        <div>
            <h6 class="fw-bold">📦 Detail Pesanan</h6>
            <div class="table-responsive">
                <table border="1">
                    <tr>
                        <td><strong>Order ID:</strong></td>
                        <td>#{{ $orderItem->order_id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Order Item ID:</strong></td>
                        <td>#{{ $orderItem->order_item_id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>CANCELLED</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pembayaran:</strong></td>
                        <td>{{ $orderItem->paid_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alasan Cancel:</strong></td>
                        <td>{{ $orderItem->getCancelReason() }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <div class="product-detail-section">
            <h4>🛍️ Detail Produk</h4>
            <table border="1">
                <tr>
                    <td rowspan="5" style="text-align: center;">
                        @if ($orderItem->product->product_img)
                            <img src="{{ asset('storage/products/' . $orderItem->product->product_img) }}" alt=""
                                width="150" height="150">
                        @endif
                    </td>
                    <td><strong>Nama Produk:</strong></td>
                    <td>{{ $orderItem->product->product_name }}</td>
                </tr>
                <tr>
                    <td><strong>Toko:</strong></td>
                    <td>{{ $orderItem->shop->shop_name }}</td>
                </tr>
                <tr>
                    <td><strong>Harga Satuan:</strong></td>
                    <td>Rp {{ number_format($orderItem->product_price, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Jumlah:</strong></td>
                    <td>{{ $orderItem->quantity }}</td>
                </tr>
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td><strong>Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <hr>

        <div class="buyer-detail-section">
            <h6 class="fw-bold">👤 Detail Buyer</h6>
            <div class="table-responsive">
                <table border="1">
                    <tr>
                        <td><strong>Username:</strong></td>
                        <td>{{ $orderItem->order->account->username }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $orderItem->order->account->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>User ID:</strong></td>
                        <td>{{ $orderItem->order->account->user_id }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        @if ($orderItem->complaint)
            <div class="complaint-detail-section">
                <h6 class="fw-bold">📝 Detail Complaint</h6>
                <div class="table-responsive">
                    <table border="1">
                        <tr>
                            <td><strong>Complaint ID:</strong></td>
                            <td>#{{ $orderItem->complaint->complaint_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>{{ ucfirst($orderItem->complaint->status) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Keputusan:</strong></td>
                            <td>
                                @if ($orderItem->complaint->decision === 'refund')
                                    <span style="color: green;">✓ REFUND DISETUJUI</span>
                                @elseif($orderItem->complaint->decision === 'reject')
                                    <span style="color: red;">✗ DITOLAK</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Deskripsi Masalah:</strong></td>
                            <td>{{ $orderItem->complaint->description }}</td>
                        </tr>
                        @if ($orderItem->complaint->is_auto_resolved)
                            <tr>
                                <td colspan="2" style="background: yellow;">
                                    <strong>⚠️ Auto-Resolved:</strong> Seller tidak merespons dalam 24 jam
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

                <p>
                    <a href="{{ route('admin.complaints.show', $orderItem->complaint->complaint_id) }}">
                        → Lihat Detail Complaint Lengkap
                    </a>
                </p>
            </div>
            <hr>
        @endif

        <div class="refund-action-section">
            <h6 class="fw-bold">💰 Aksi Refund</h6>

            @if ($orderItem->is_refunded)
                <div style="background: lightgreen; padding: 15px; border: 2px solid green;">
                    <p><strong>✓ Pesanan ini sudah ditandai sebagai REFUNDED</strong></p>
                    <p>Buyer sudah menerima refund sebesar: <strong>Rp
                            {{ number_format($orderItem->subtotal, 0, ',', '.') }}</strong></p>
                </div>

                <form action="{{ route('admin.cancelled_orders.undo_refunded', $orderItem->order_item_id) }}"
                    method="POST" style="margin-top: 15px;">
                    @csrf
                    <button type="submit" style="background: orange; color: white; padding: 10px 20px;"
                        onclick="return confirm('Batalkan status refund? Ini untuk keperluan koreksi data.')">
                        ↺ Batalkan Status Refund
                    </button>
                </form>
            @else
                <div style="background: lightyellow; padding: 15px; border: 2px solid orange;">
                    <p><strong>⚠️ Pesanan ini BELUM ditandai sebagai refunded</strong></p>
                    <p>Jumlah yang harus direfund: <strong>Rp
                            {{ number_format($orderItem->subtotal, 0, ',', '.') }}</strong></p>
                </div>

                <form action="{{ route('admin.cancelled_orders.mark_refunded', $orderItem->order_item_id) }}"
                    method="POST" style="margin-top: 15px;">
                    @csrf
                    <button type="submit" style="background: green; color: white; padding: 10px 20px;"
                        onclick="return confirm('Tandai pesanan ini sebagai REFUNDED?\n\nIni hanya untuk keperluan tracking admin.')">
                        ✓ Tandai Sebagai REFUNDED
                    </button>
                </form>
            @endif
        </div>

        <hr>

        <div class="actions">
            <a href="{{ route('admin.cancelled_orders.index') }}">
                ← Kembali ke Daftar Pesanan Dibatalkan
            </a>
        </div>
    </div>
@endsection
