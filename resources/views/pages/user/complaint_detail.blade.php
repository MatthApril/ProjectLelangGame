@extends('layouts.template')

@section('title', 'Detail Komplain')

@section('content')
<div>
    <h2>Detail Komplain #{{ $complaint->complaint_id }}</h2>
    <hr>

    <div>
        <strong>Status:</strong>
        <span>
            @if($complaint->status === 'waiting_seller')
                Menunggu Tanggapan Seller
            @elseif($complaint->status === 'waiting_admin')
                Menunggu Keputusan Admin
            @else
                Selesai
            @endif
        </span>

        @if($complaint->decision)
            <span>
                @if($complaint->decision === 'refund')
                    ✓ REFUND DISETUJUI
                @else
                    ✗ KOMPLAIN DITOLAK
                @endif
            </span>
        @endif
    </div>

    <div>
        <h4>Detail Produk</h4>
        <table>
            <tr>
                <td>
                    @if($complaint->orderItem->product->product_img)
                        <img src="{{ asset('storage/' . $complaint->orderItem->product->product_img) }}" alt="" width="100" height="100" class="img-fluid">
                    @endif
                </td>
                <td>
                    <p><strong>Nama Produk:</strong> {{ $complaint->orderItem->product->product_name }}</p>
                    <p><strong>Toko:</strong> {{ $complaint->orderItem->shop->shop_name }}</p>
                    <p><strong>Jumlah:</strong> {{ $complaint->orderItem->quantity }}</p>
                    <p><strong>Total:</strong> Rp {{ number_format($complaint->orderItem->subtotal, 0, ',', '.') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <h4>📝 Komplain Anda</h4>
        <p><strong>Tanggal:</strong> {{ $complaint->created_at->format('d M Y H:i') }}</p>
        <p><strong>Deskripsi Masalah:</strong></p>
        <div>{{ $complaint->description }}</div>

        <p><strong>Bukti Foto:</strong></p>
        <img src="{{ asset('storage/' . $complaint->proof_img) }}" alt="Bukti" onclick="window.open(this.src, '_blank')">
        <br><small>Klik gambar untuk memperbesar</small>
    </div>

    @if($complaint->response)
        <div>
            <h4>💬 Tanggapan Seller</h4>
            <p><strong>Tanggal:</strong> {{ $complaint->response->created_at->format('d M Y H:i') }}</p>
            <p><strong>Pembelaan Seller:</strong></p>
            <div>{{ $complaint->response->message }}</div>

            @if($complaint->response->attachment)
                <p><strong>Lampiran:</strong></p>
                <a href="{{ asset('storage/' . $complaint->response->attachment) }}" target="_blank" >
                    📎 Lihat Lampiran
                </a>
            @endif
        </div>
    @else
        <div>
            <p>Seller belum memberikan tanggapan</p>
        </div>
    @endif

    @if($complaint->status === 'resolved')
        <div>
            <h4>⚖️ Keputusan {{ $complaint->is_auto_resolved ? 'Otomatis (Sistem)' : 'Admin' }}</h4>

            @if($complaint->is_auto_resolved)
                <p><strong>⚠️ Seller tidak merespons dalam 24 jam</strong></p>
            @endif

            <p><strong>Tanggal:</strong> {{ $complaint->resolved_at->format('d M Y H:i') }}</p>
            <p>
                @if($complaint->decision === 'refund')
                    <strong>✓ KOMPLAIN DISETUJUI - REFUND DIBERIKAN</strong>
                    <br><small>Saldo Anda telah ditambahkan sebesar Rp {{ number_format($complaint->orderItem->subtotal, 0, ',', '.') }}</small>

                    @if($complaint->is_auto_resolved)
                        <br><small>📌 Refund otomatis karena seller tidak merespons</small>
                    @endif
                @else
                    <strong>✗ KOMPLAIN DITOLAK</strong>
                    <br><small>Setelah meninjau bukti dari kedua pihak, admin memutuskan komplain tidak dapat disetujui</small>
                @endif
            </p>
        </div>
    @else
        <div>
            <strong>ℹ️ Status:</strong>
            @if($complaint->status === 'waiting_seller')
                Menunggu tanggapan dari seller. Seller memiliki 3x24 jam untuk merespons.
            @elseif($complaint->status === 'waiting_admin')
                Komplain sedang ditinjau oleh admin. Admin akan memutuskan dalam 3x24 jam.
            @endif
        </div>
    @endif

    <div>
        <a href="{{ route('user.complaints.index') }}">
            ← Kembali ke Daftar Komplain
        </a>
    </div>
</div>
@endsection
