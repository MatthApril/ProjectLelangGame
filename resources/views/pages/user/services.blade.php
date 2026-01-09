@extends('layouts.template')

@section('title', 'Layanan | LelangGame')

@section('content')
    <div class="container my-4">
        <nav nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pusat Bantuan</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-semibold fs-2">Pusat Bantuan</span>
            <a href="{{ route('support.index') }}" class="btn btn-outline-primary"><i class="bi bi-headset"></i> Hubungi Kita</a>
        </div>
        <hr>
        <div class="card mb-4">
            <div class="card-header">
                <span class="fw-semibold fs-5">Untuk Pembeli</span>
            </div>
            <div class="card-body">
                <div class="accordion" id="accordionBuyer">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Bagaimana cara mengikuti lelang?
                        </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionBuyer">
                            <div class="accordion-body">
                                Cari item yang Anda inginkan, masuk ke halaman detail, lalu masukkan nominal tawaran pada kolom yang tersedia. Pastikan tawaran Anda lebih tinggi dari "Harga Saat Ini" minimal sebesar Rp 1.000 (kelipatan bid).
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Apa yang terjadi jika saya memenangkan lelang?
                        </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionBuyer">
                            <div class="accordion-body">
                                Selamat! Anda akan menerima notifikasi dan tagihan pembayaran. Anda wajib melunasi pembayaran dalam waktu [1x24 Jam] via Midtrans (Gopay, OVO, Virtual Account). Jika telat, akun Anda akan diberikan sanksi.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Apakah uang saya aman jika penjual tidak mengirim item?
                        </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionBuyer">
                            <div class="accordion-body">
                                Sangat aman. Uang pembayaran Anda ditahan di Rekening Bersama (Escrow) sistem kami. Dana baru akan diteruskan ke penjual setelah Anda mengonfirmasi bahwa item telah diterima dengan baik.
                            </div>
                        </div>
                    </div>
                    {{-- <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Apa itu "Soft Close" / Perpanjangan Waktu?
                        </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionBuyer">
                            <div class="accordion-body">
                                Jika ada tawaran masuk di [60 detik] terakhir sebelum lelang berakhir, timer akan otomatis bertambah [60 detik]. Ini dilakukan agar semua orang punya kesempatan adil untuk menawar.
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <span class="fw-semibold fs-5">Untuk Penjual</span>
            </div>
            <div class="card-body">
                <div class="accordion" id="accordionSeller">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne2" aria-expanded="false" aria-controls="collapseOne2">
                            Berapa biaya admin untuk menjual item?
                        </button>
                        </h2>
                        <div id="collapseOne2" class="accordion-collapse collapse" data-bs-parent="#accordionSeller">
                            <div class="accordion-body">
                                Platform kami mengenakan biaya admin sebesar 5% dari total harga penjualan. Biaya ini akan dipotong secara otomatis dari dana hasil penjualan Anda sebelum ditransfer ke saldo toko. Biaya admin mencakup layanan escrow, keamanan transaksi, dan dukungan pelanggan.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo2" aria-expanded="false" aria-controls="collapseTwo2">
                            Kapan saya menerima uang hasil penjualan?
                        </button>
                        </h2>
                        <div id="collapseTwo2" class="accordion-collapse collapse" data-bs-parent="#accordionSeller">
                            <div class="accordion-body">
                                Dana hasil penjualan akan masuk ke saldo toko Anda setelah pembeli mengonfirmasi penerimaan item atau setelah batas waktu konfirmasi otomatis (3x24 jam setelah pengiriman). Anda dapat melakukan penarikan (withdraw) ke rekening bank yang terdaftar kapan saja dengan minimum penarikan Rp 50.000.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree2" aria-expanded="false" aria-controls="collapseThree2">
                            Apa yang boleh dan tidak boleh dilakukan saat lelang berjalan?
                        </button>
                        </h2>
                        <div id="collapseThree2" class="accordion-collapse collapse" data-bs-parent="#accordionSeller">
                            <div class="accordion-body">
                                <strong>Boleh:</strong> Memantau lelang, menjawab pertanyaan pembeli melalui fitur chat, memperbarui deskripsi jika diperlukan.<br>
                                <strong>Tidak Boleh:</strong> Membatalkan lelang yang sudah berjalan, menawar di lelang sendiri (bid shill), membuat kesepakatan di luar platform, atau mengubah harga awal setelah ada tawaran. Pelanggaran dapat mengakibatkan sanksi hingga pemblokiran akun.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <span class="fw-semibold fs-5">Akun & Keamanan</span>
            </div>
            <div class="card-body">
                <div class="accordion" id="accordionSafety">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne3" aria-expanded="false" aria-controls="collapseOne3">
                            Mengapa saya tidak bisa melakukan penawaran?
                        </button>
                        </h2>
                        <div id="collapseOne3" class="accordion-collapse collapse" data-bs-parent="#accordionSafety">
                            <div class="accordion-body">
                                Ada beberapa alasan: (1) Anda belum memverifikasi email/no. HP, (2) Anda memiliki tagihan lelang sebelumnya yang belum dibayar, atau (3) Anda mencoba menawar di lelang milik sendiri (dilarang).
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo3" aria-expanded="false" aria-controls="collapseTwo3">
                            Bagaimana jika terjadi masalah transaksi?
                        </button>
                        </h2>
                        <div id="collapseTwo3" class="accordion-collapse collapse" data-bs-parent="#accordionSafety">
                            <div class="accordion-body">
                                Jangan panik. Klik tombol "Layanan" di menu samping dan buat Tiket Bantuan Baru. Admin kami akan menjadi penengah dan memeriksa bukti chat/transaksi dari kedua belah pihak.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
