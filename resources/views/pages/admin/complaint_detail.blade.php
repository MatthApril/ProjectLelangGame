@extends('layouts.templateadmin')

@section('title', 'Review Komplain')

@section('content')
    <div class="container my-3 text-dark">
        <h5 class="fw-semibold text-dark">Review Komplain #{{ $complaint->complaint_id }}</h5>
        <hr>

        @if (session('success'))
            <div>{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div>{{ session('error') }}</div>
        @endif

        <div>
            <strong>Status:</strong>
            <span>
                @if ($complaint->status === 'waiting_admin')
                    ⚠️ PERLU KEPUTUSAN ADMIN
                @elseif($complaint->status === 'resolved')
                    ✓ SUDAH DIPUTUSKAN
                @else
                    {{ ucfirst($complaint->status) }}
                @endif
            </span>

            @if ($complaint->decision)
                <span>
                    @if ($complaint->decision === 'refund')
                        ✓ REFUND DISETUJUI
                    @else
                        ✗ KOMPLAIN DITOLAK
                    @endif
                </span>
            @endif
        </div>

        <div>
            <h4 class="fw-bold">📦 Detail Transaksi</h4>
            <table>
                <tr>
                    <td>
                        @if ($complaint->orderItem->product->product_img)
                            <img src="{{ asset('storage/' . $complaint->orderItem->product->product_img) }}" alt=""
                                width="100" height="100">
                        @endif
                    </td>
                    <td>
                        <p><strong>Produk:</strong> {{ $complaint->orderItem->product->product_name }}</p>
                        <p><strong>Toko:</strong> {{ $complaint->orderItem->product->shop->shop_name }}</p>
                        <p><strong>Buyer:</strong> {{ $complaint->buyer->username }} (ID: {{ $complaint->buyer->user_id }})
                        </p>
                        <p><strong>Seller:</strong> {{ $complaint->seller->username }} (ID:
                            {{ $complaint->seller->user_id }})</p>
                        <p><strong>Jumlah:</strong> {{ $complaint->orderItem->quantity }}</p>
                        <p><strong>Nilai Transaksi:</strong> Rp
                            {{ number_format($complaint->orderItem->subtotal, 0, ',', '.') }}</p>
                        <p><strong>Order ID:</strong> #{{ $complaint->orderItem->order_id }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div>
            <h4 class="fw-bold">👤 BUKTI DARI BUYER</h4>
            <p><strong>Tanggal Komplain:</strong> {{ $complaint->created_at->format('d M Y H:i') }}</p>
            <p><strong>Deskripsi Masalah:</strong> {{ $complaint->description }}</p>

            <p><strong>Bukti Foto:</strong></p>
            <img src="{{ asset('storage/' . $complaint->proof_img) }}" alt="Bukti Buyer"
                onclick="window.open(this.src, '_blank')">
            <br><small>Klik gambar untuk memperbesar</small>
        </div>

        @if ($complaint->response)
            <div>
                <h4 class="fw-bold">🛒 PEMBELAAN DARI SELLER</h4>
                <p><strong>Tanggal Tanggapan:</strong> {{ $complaint->response->created_at->format('d M Y H:i') }}</p>
                <p><strong>Pembelaan:</strong> {{ $complaint->response->message }}</p>

                @if ($complaint->response->attachment)
                    <p><strong>Lampiran Seller:</strong></p>
                    @php
                        $ext = pathinfo($complaint->response->attachment, PATHINFO_EXTENSION);
                    @endphp

                    @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                        <img src="{{ asset('storage/' . $complaint->response->attachment) }}" alt="Lampiran Seller"
                            onclick="window.open(this.src, '_blank')">
                        <br><small>Klik gambar untuk memperbesar</small>
                    @else
                        <a href="{{ asset('storage/' . $complaint->response->attachment) }}" target="_blank">
                            📎 Lihat Lampiran PDF
                        </a>
                    @endif
                @endif
            </div>
        @else
            <div>
                <p>Seller belum memberikan tanggapan</p>
            </div>
        @endif
        @if ($complaint->status === 'waiting_admin' && $complaint->status !== 'resolved')
            <div>
                <h4 class="fw-bold">⚖️ KEPUTUSAN ADMIN</h4>

                <div>
                    <strong>⚠️ Pertimbangan:</strong>
                    <ul>
                        <li><strong>Refund:</strong> Buyer akan menerima Rp
                            {{ number_format($complaint->orderItem->subtotal, 0, ',', '.') }} kembali</li>
                        <li><strong>Refund:</strong> running transactions seller akan dikurangi Rp
                            {{ number_format($complaint->orderItem->subtotal, 0, ',', '.') }}</li>
                        <li><strong>Refund:</strong> Order item status menjadi "cancelled"</li>
                        <li><strong>Reject:</strong> Tidak ada perubahan transaksi</li>
                        <li><strong>Keputusan bersifat FINAL dan tidak dapat dibatalkan</strong></li>
                    </ul>
                </div>

                <form action="{{ route('admin.complaints.resolve', $complaint->complaint_id) }}" method="POST">
                    @csrf

                    <div>
                        <label><strong>Pilih Keputusan:</strong></label>

                        <label>
                            <input type="radio" name="decision" value="refund" required>
                            <strong>✓ SETUJU REFUND BUYER</strong>
                            <br><small>Buyer menerima uang kembali, seller rugi</small>
                        </label>

                        <label>
                            <input type="radio" name="decision" value="reject" required>
                            <strong>✗ TOLAK KOMPLAIN</strong>
                            <br><small>Transaksi tetap berjalan, tidak ada refund</small>
                        </label>

                        @error('decision')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit"
                        onclick="return confirm('YAKIN PUTUSKAN KOMPLAIN INI?\n\nKeputusan bersifat FINAL dan akan langsung dieksekusi!')" class="btn btn-primary">
                        PUTUSKAN KOMPLAIN
                    </button>
                </form>
            </div><br>
        @elseif($complaint->status === 'resolved')
            <div>
                <h4 class="fw-bold">⚖️ KEPUTUSAN FINAL {{ $complaint->is_auto_resolved ? '(AUTO-RESOLVED)' : '(MANUAL)' }}
                </h4>

                @if ($complaint->is_auto_resolved)
                    <p><strong>⚠️ Seller tidak merespons dalam 24 jam</strong></p>
                @endif

                <p><strong>Tanggal:</strong> {{ $complaint->resolved_at->format('d M Y, H:i:s') }}</p>
                <p>
                    @if ($complaint->decision === 'refund')
                        <strong>✓ KOMPLAIN DISETUJUI - BUYER DIREFUND</strong>
                        <br><small>• Saldo buyer bertambah: Rp
                            {{ number_format($complaint->orderItem->subtotal, 0, ',', '.') }}</small>
                        <br><small>• Running transactions seller berkurang: Rp
                            {{ number_format($complaint->orderItem->subtotal, 0, ',', '.') }}</small>
                        <br><small>• Status order_item: CANCELLED</small>

                        @if ($complaint->is_auto_resolved)
                            <br><small>📌 Refund otomatis karena seller tidak merespons</small>
                        @endif
                    @else
                        <strong>✗ KOMPLAIN DITOLAK</strong>
                        <br><small>Transaksi tetap berjalan normal. Tidak ada refund.</small>
                    @endif
                </p>
            </div>
        @endif

        <div>
            <a href="{{ route('admin.complaints.index') }}" class="btn btn-primary">
                ← Kembali ke Daftar Komplain
            </a>
        </div>
    </div>
@endsection
