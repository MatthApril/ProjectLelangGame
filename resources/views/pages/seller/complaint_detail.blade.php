@extends('layouts.template')

@section('title', 'Detail Komplain')

@section('content')
<div>
    <h2>Detail Komplain #{{ $complaint->complaint_id }}</h2>
    <hr>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div>{{ session('error') }}</div>
    @endif

    <div>
        <strong>Status:</strong>
        <span>
            @if($complaint->status === 'waiting_seller')
                ⚠️ PERLU TANGGAPAN ANDA
            @elseif($complaint->status === 'waiting_admin')
                Menunggu Keputusan Admin
            @else
                Selesai
            @endif
        </span>

        @if($complaint->decision)
            <span>
                @if($complaint->decision === 'refund')
                    ✗ BUYER DIREFUND
                @else
                    ✓ KOMPLAIN DITOLAK
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
                    <p><strong>Buyer:</strong> {{ $complaint->buyer->username }}</p>
                    <p><strong>Jumlah:</strong> {{ $complaint->orderItem->quantity }}</p>
                    <p><strong>Total:</strong> Rp {{ number_format($complaint->orderItem->subtotal, 0, ',', '.') }}</p>
                    <p><strong>Status Order:</strong> 
                        @if($complaint->orderItem->status === 'cancelled')
                            Dibatalkan (Refund)
                        @else
                            {{ ucfirst($complaint->orderItem->status) }}
                        @endif
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <h4>📝 Komplain dari Buyer</h4>
        <p><strong>Tanggal:</strong> {{ $complaint->created_at->format('d M Y H:i') }}</p>
        <p><strong>Deskripsi Masalah:</strong></p>
        <div>{{ $complaint->description }}</div>
        
        <p><strong>Bukti Foto dari Buyer:</strong></p>
        <img src="{{ asset('storage/' . $complaint->proof_img) }}"alt="Bukti"onclick="window.open(this.src, '_blank')">
        <br><small>Klik gambar untuk memperbesar</small>
    </div>

    @if($complaint->status === 'waiting_seller' && !$complaint->response()->exists())
        <div>
            <h4>💬 Berikan Tanggapan Anda</h4>
            
            <div>
                <strong>⚠️ Penting:</strong>
                <ul>
                    <li>Berikan penjelasan <strong>jelas dan objektif</strong></li>
                    <li>Lampirkan <strong>bukti pendukung</strong> jika ada (opsional)</li>
                    <li>Tanggapan Anda akan ditinjau oleh <strong>Admin</strong></li>
                    <li>Keputusan admin bersifat <strong>final</strong></li>
                    <li>Batas waktu respons: {{ $complaint->created_at->addDay()->format('d M Y H:i') }}</li>
                    <li><strong>Jika tidak respons dalam 24 jam, complaint otomatis DISETUJUI dan buyer akan DIREFUND!</strong></li>
                </ul>
            </div>

            <form action="{{ route('seller.complaints.respond', $complaint->complaint_id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div>
                    <label><strong>Pembelaan/Klarifikasi *</strong></label>
                    <textarea name="message" rows="5" required maxlength="1000" placeholder="Jelaskan posisi Anda dengan detail (minimal 20 karakter)">{{ old('message') }}</textarea>
                    @error('message')
                        <small>{{ $message }}</small>
                    @enderror
                    <small>Minimal 20 karakter, maksimal 1000 karakter</small>
                </div>

                <div>
                    <label><strong>Lampiran Pendukung (Opsional)</strong></label>
                    <input type="file" name="attachment" accept="image/jpeg,image/png,image/jpg,application/pdf">
                    @error('attachment')
                        <small>{{ $message }}</small>
                    @enderror
                    <small>Format: JPG, PNG, PDF | Maksimal 5MB</small>
                </div>

                <button type="submit" onclick="return confirm('Pastikan tanggapan Anda sudah benar. Kirim tanggapan?')">
                    Kirim Tanggapan
                </button>
            </form>
        </div>
    @elseif($complaint->response)
        <div>
            <h4>💬 Tanggapan Anda</h4>
            <p><strong>Tanggal Dikirim:</strong> {{ $complaint->response->created_at->format('d M Y H:i') }}</p>
            <p><strong>Pembelaan:</strong></p>
            <div>{{ $complaint->response->message }}</div>
            
            @if($complaint->response->attachment)
                <p><strong>Lampiran:</strong></p>
                <a href="{{ asset('storage/' . $complaint->response->attachment) }}" target="_blank">
                    📎 Lihat Lampiran
                </a>
            @endif

            @if($complaint->status === 'waiting_admin')
                <div>
                    <strong>ℹ️ Status:</strong> Tanggapan Anda sudah dikirim. Menunggu keputusan admin.
                </div>
            @endif
        </div>
    @endif

    @if($complaint->status === 'resolved')
        <div>
            <h4>⚖️ Keputusan {{ $complaint->is_auto_resolved ? 'Otomatis (Sistem)' : 'Admin' }}</h4>
            @if($complaint->is_auto_resolved)
                <p><strong>⚠️ ANDA TIDAK MERESPONS DALAM 24 JAM!</strong></p>
            @endif
            <p><strong>Tanggal:</strong> {{ $complaint->resolved_at->format('d M Y H:i') }}</p>
            <p>
                @if($complaint->decision === 'refund')
                    <strong>✗ KOMPLAIN DISETUJUI - BUYER DIREFUND</strong>
                    <br><small>Saldo buyer telah dikembalikan sebesar Rp {{ number_format($complaint->orderItem->subtotal, 0, ',', '.') }}</small>
                    <br><small>⚠️ Uang akan dipotong dari running_transactions Anda</small>

                    @if($complaint->is_auto_resolved)
                        <br><small>📌 Refund otomatis karena Anda tidak merespons dalam 24 jam</small>
                    @endif
                @else
                    <strong>✓ KOMPLAIN DITOLAK - TIDAK ADA REFUND</strong>
                    <br><small>Setelah meninjau bukti dari kedua pihak, admin memutuskan komplain tidak dapat disetujui</small>
                @endif
            </p>
        </div>
    @endif

    <div>
        <a href="{{ route('seller.complaints.index') }}">
            ← Kembali ke Daftar Komplain
        </a>
    </div>
</div>
@endsection