@extends('layouts.template')

@section('title', 'Detail Komplain | LelangGame')

@section('content')
<div class="container">
        <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb mt-3">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard Seller</a></li>
                <li class="breadcrumb-item"><a href="{{ route('seller.complaints.index') }}">Komplain Pelanggan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Komplain</li>
            </ol>
        </nav>
    <h2 class="fw-semibold">Detail Komplain #{{ $complaint->complaint_id }}</h2>
    <hr>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @error('message')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @enderror
    @error('attachment')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @enderror
                <div class="card p-3">
                    <div class="row">
                        <div class="col-md-5 text-center my-2">
                            @if ($complaint->orderItem->product->product_img)
                                <img src="{{ asset('storage/products/' . $complaint->orderItem->product->product_img) }}" alt=""
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
                            {{-- <div class="d-flex align-items-center gap-1">
                                <h4 class="fw-bold text-primary mb-0">
                                    Rp {{ number_format($complaint->orderItem->product->price, 0, ',', '.') }}
                                </h4>
                                <p class="text-secondary mb-0">
                                    / {{ $complaint->orderItem->product->category->category_name }}
                                </p>
                            </div> --}}
                            <p class="m-0">Jumlah : <span class="fw-semibold">{{ $complaint->orderItem->quantity }}</span></p>
                            <p class="m-0">Subtotal : <span class="fw-semibold">{{ $complaint->orderItem->subtotal }}</span></p>
                            <hr>
                            <p class="m-0 fw-semibold">Pembeli : {{ $complaint->buyer->username }}</p>
                            {{-- <div class="row d-flex align-items-center">

                                <div class="col-8">
                                    <div class="d-flex align-items-center gap-2">

                                        <div>
                                            @if ($complaint->orderItem->product->shop->shop_img)
                                                <img src="{{ asset('storage/shops/' . $complaint->orderItem->product->shop->shop_img) }}"
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

                            </div> --}}
                            <hr>
                            <button type="submit" class="btn btn-outline-primary text-center">
                                <i class="bi bi-chat"></i> Hubungi Pembeli
                            </button>
                        </div>
                    </div>
                    <h4 class="fw-semibold mt-3">Komplain Pembeli</h4>
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
                        <a href="{{ asset('storage/complaints/' . $complaint->proof_img) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-card-image"></i> Lihat Bukti Pembeli
                        </a>
                    </div>
                    {{-- <div>
                        <img src="{{ asset('storage/complaints/' . $complaint->proof_img) }}" alt="" onclick="window.open(this.src, '_blank')" class="img-fluid rounded shadow" width="400">
                        <p><i>Klik Gambar Untuk Memperbesar.</i></p>
                    </div> --}}
                    @if($complaint->status === 'waiting_seller' && !$complaint->response()->exists())
                    <div>
                        <h4 class="fw-semibold mt-5">Berikan Tanggapan Anda</h4>
                        <div>
                            <strong><i class="bi bi-exclamation-triangle-fill"></i> Penting :</strong>
                            <ul>
                                <li>Berikan penjelasan secara <strong>jelas, jujur, dan objektif</strong>.</li>
                                <li>Lampirkan <strong>bukti pendukung</strong> apabila diperlukan (opsional).</li>
                                <li>Tanggapan Anda akan <strong>ditinjau oleh Admin</strong> sebelum keputusan diambil.</li>
                                <li>Keputusan yang diberikan oleh Admin bersifat <strong>final</strong>.</li>
                                <li>
                                    Batas waktu pemberian tanggapan:
                                    <strong>{{ $complaint->created_at->addDay()->format('d M Y H:i') }}</strong>.
                                </li>
                                <li>
                                    Apabila tidak ada tanggapan hingga batas waktu tersebut,
                                    komplain akan <strong>diproses secara otomatis oleh sistem</strong>
                                    sesuai dengan ketentuan yang berlaku.
                                </li>
                            </ul>
                        </div>
                        <hr>
                        <form action="{{ route('seller.complaints.respond', $complaint->complaint_id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <label>Pembelaan / Klarifikasi *</label>
                                <textarea name="message" rows="5" required maxlength="1000" placeholder="Jelaskan Pembelaan Anda Dengan Detail (Minimal 20 Karakter)" class="form-control">{{ old('message') }}</textarea>
                                <p><i>Minimal 20 Karakter, Maksimal 1000 Karakter</i></p>
                            </div>

                            <div class="mt-3">
                                <label>Lampiran Pendukung (Opsional)</label>
                                <input type="file" name="attachment" accept="image/jpeg,image/png,image/jpg,application/pdf" class="form-control">
                                <p><i>Format: JPG, PNG, JPEG | Maksimal 2MB</i></p>
                            </div>

                            <button type="submit" onclick="return confirm('Pastikan tanggapan Anda sudah benar. Kirim tanggapan?')" class="btn btn-success float-end">
                                Kirim Tanggapan <i class="bi bi-caret-right-fill"></i>
                            </button>
                        </form>
                    </div>
                @elseif($complaint->response)
                        <div>
                            <h4 class="fw-semibold mt-5">Tanggapan Anda</h4>
                            <p class="m-0 text-secondary">Tanggal : {{ $complaint->response->created_at->format('d M Y H:i') }}</p>
                            <hr>
                            <label>Pembelaan Seller :</label>
                            <div class="card p-3 mb-3">{{ $complaint->response->message }}</div>

                            @if($complaint->response->attachment)
                                <label>Bukti Foto :</label>
                                {{-- <div>
                                    <img src="{{ asset('storage/complaints/' . $complaint->response->attachment) }}" alt="" onclick="window.open(this.src, '_blank')" class="img-fluid rounded shadow" width="400">
                                    <p><i>Klik Gambar Untuk Memperbesar.</i></p>
                                </div> --}}
                                <div>
                                    <a href="{{ asset('storage/complaints/' . $complaint->response->attachment) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-card-image"></i> Lihat Bukti Saya
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($complaint->status === 'resolved')
                    <div class="mt-5">
                        <h4 class="fw-semibold">Keputusan {{ $complaint->is_auto_resolved ? 'Otomatis (Sistem)' : 'Admin' }}</h4>
                        <p class="text-secondary">{{ $complaint->resolved_at->format('d M Y H:i') }}</p>
                        <hr>
                        <div class="card p-3">
                            @if($complaint->decision)
                                <span>
                                    @if($complaint->decision === 'refund')
                                    <p class="m-0 fw-semibold">
                                        <i class="bi bi-check-lg text-danger"></i>
                                        Pembeli Di-Refund – Komplain Disetujui
                                    </p>
                                    @else
                                        <p class="m-0 fw-semibold">
                                            <i class="bi bi-check-lg text-danger"></i>
                                            Komplain Ditolak – Pembeli Tidak Di-Refund
                                        </p>
                                    @endif
                                </span>
                            @endif
                        </div>
                        {{-- <div class="card p-3">
                                @if($complaint->is_auto_resolved)
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
                                            Refund diberikan secara otomatis karena seller tidak memberikan respons dalam batas waktu yang ditentukan.
                                        </p>
                                    @endif
                                @else
                                    <p class="m-0 fw-semibold">
                                        <i class="bi bi-x-lg text-danger"></i>
                                        Komplain Tidak Disetujui
                                    </p>

                                    <p class="text-secondary m-0">
                                        Setelah meninjau bukti dan keterangan dari kedua belah pihak, admin memutuskan bahwa komplain ini
                                        <strong>tidak dapat disetujui</strong>.
                                    </p>
                                @endif
                            </div>
                        </div> --}}
                    @else
                        <hr>
                        <div>
                            <strong><i class="bi bi-info-circle-fill"></i> Status:</strong>
                            @if ($complaint->status === 'waiting_seller')
                                Komplain ini <strong>menunggu tanggapan Anda</strong>.
                                Harap berikan tanggapan dalam waktu maksimal <strong>24 jam</strong> sejak komplain diajukan.
                            @elseif ($complaint->status === 'waiting_admin')
                                Tanggapan Anda telah diterima dan komplain sedang <strong>ditinjau oleh admin</strong>.
                                Admin akan memberikan keputusan akhir dalam waktu maksimal <strong>3 × 24 jam</strong>.
                            @else
                                Komplain ini telah <strong>diselesaikan</strong>.
                            @endif
                        </div>
                    @endif
                </div>
{{--
    <div>
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
                        <img src="{{ asset('storage/products/' . $complaint->orderItem->product->product_img) }}" alt="" width="100" height="100" class="img-fluid">
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
        <img src="{{ asset('storage/complaints/' . $complaint->proof_img) }}"alt="Bukti"onclick="window.open(this.src, '_blank')">
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
                <a href="{{ asset('storage/complaints/' . $complaint->response->attachment) }}" target="_blank">
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
    @endif --}}
</div>
@endsection
