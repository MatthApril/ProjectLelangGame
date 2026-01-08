<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            // 1. SELAMAT DATANG PENGGUNA BARU
            [
                'code_tag' => 'selamat_datang_pengguna',
                'title' => 'Pesan Selamat Datang',
                'subject' => 'Selamat Datang! Mulai Petualangan dan Menangkan Lelang Pertamamu',
                'body' => "Hai {username}!\n\nSelamat datang di keluarga besar kami! Terima kasih telah mempercayakan kebutuhan gaming kamu di sini.\n\nAkun kamu telah berhasil dibuat dan sekarang kamu siap untuk menjelajahi ribuan produk game dan layanan digital termurah, aman, dan terpercaya.\n\n🔥 Fitur Unggulan: Balai Lelang (Auction House)\nJangan lewatkan keseruan di fitur Lelang kami!\n\nHappy Trading & Bidding!",
                'trigger_type' => 'transactional',
                'category' => 'system',
            ],

            // 2. SELAMAT DATANG PENJUAL BARU
            [
                'code_tag' => 'selamat_datang_penjual',
                'title' => 'Sambutan Penjual Baru',
                'subject' => 'Selamat! Toko Kamu Resmi Dibuka 🎉',
                'body' => "Halo {username}!\n\nSelamat! Kamu telah resmi terdaftar sebagai penjual. Langkah pertama menuju kesuksesan bisnismu dimulai hari ini.\n\nKini kamu bisa mulai mengunggah produk, mengatur etalase, dan menjangkau ribuan gamers yang siap membeli produkmu.\n\nSemoga laris manis!",
                'trigger_type' => 'transactional',
                'category' => 'system',
            ],

            // 3. PEMBAYARAN BERHASIL (ke Pembeli)
            [
                'code_tag' => 'pembayaran_berhasil',
                'title' => 'Pembayaran Pesanan Berhasil',
                'subject' => 'Pembayaran Berhasil #{order_id}',
                'body' => "Halo {username}!\n\nPembayaranmu sebesar Rp {amount} telah diterima.\n\nPesananmu dengan ID #{order_id} sedang diproses oleh penjual. Kamu akan menerima notifikasi saat pesanan dikirim.\n\nTerima kasih telah berbelanja!",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 4. PESANAN BARU (ke Penjual)
            [
                'code_tag' => 'pesanan_baru',
                'title' => 'Pesanan Baru Diterima',
                'subject' => 'Pesanan Baru Masuk! #{order_id}',
                'body' => "Halo {username}!\n\nAda pesanan baru masuk ke tokomu!\n\nID Pesanan: #{order_id}\nProduk: {product_name}\nJumlah: {quantity}\nTotal: Rp {amount}\n\nSegera proses dan kirim pesanan untuk menjaga reputasi tokomu!",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 5. PESANAN DIKIRIM (ke Pembeli)
            [
                'code_tag' => 'pesanan_dikirim',
                'title' => 'Pesanan Dikirim',
                'subject' => 'Pesananmu Sedang Dikirim! #{order_id}',
                'body' => "Halo {username}!\n\nKabar baik! Pesananmu dengan ID #{order_id} telah dikirim oleh penjual.\n\nProduk: {product_name}\n\nMohon konfirmasi penerimaan setelah barang sampai. Pesanan akan otomatis selesai dalam 3 hari jika tidak ada konfirmasi.",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 6. PESANAN DIBATALKAN (ke Pembeli)
            [
                'code_tag' => 'pesanan_dibatalkan',
                'title' => 'Pesanan Dibatalkan',
                'subject' => 'Pesanan Dibatalkan #{order_id}',
                'body' => "Halo {username}!\n\nMohon maaf, pesananmu dengan ID #{order_id} telah dibatalkan oleh penjual.\n\nProduk: {product_name}\n\nDana sebesar Rp {amount} akan dikembalikan ke saldo dompetmu dalam 1x24 jam.\n\nMohon maaf atas ketidaknyamanan ini.",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 7. PESANAN SELESAI (ke Penjual)
            [
                'code_tag' => 'pesanan_selesai',
                'title' => 'Pesanan Selesai',
                'subject' => 'Pesanan Selesai! #{order_id}',
                'body' => "Halo {username}!\n\nPesanan #{order_id} telah dikonfirmasi selesai oleh pembeli.\n\nProduk: {product_name}\nTotal: Rp {amount}\n\nDana telah masuk ke saldo tokomu. Terima kasih telah memberikan pelayanan terbaik!",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 8. PESANAN OTOMATIS SELESAI
            [
                'code_tag' => 'pesanan_otomatis_selesai',
                'title' => 'Pesanan Otomatis Selesai',
                'subject' => 'Pesanan Otomatis Selesai #{order_id}',
                'body' => "Halo {username}!\n\nPesananmu dengan ID #{order_id} telah otomatis diselesaikan oleh sistem karena tidak ada konfirmasi dalam 3 hari.\n\nJika ada masalah dengan pesanan, silakan ajukan komplain melalui menu Pesanan Saya.",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 9. TAWARAN BERHASIL (ke Penawar)
            [
                'code_tag' => 'tawaran_berhasil',
                'title' => 'Tawaran Berhasil Ditempatkan',
                'subject' => 'Tawaran Berhasil Ditempatkan!',
                'body' => "Halo {username}!\n\nTawaranmu sebesar Rp {bid_amount} pada lelang {product_name} berhasil ditempatkan!\n\nKamu saat ini menjadi penawar tertinggi. Pantau terus lelang ini agar tidak terkalahkan!\n\nLelang berakhir pada: {end_time}",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 10. TAWARAN TERKALAHKAN (ke Penawar Sebelumnya)
            [
                'code_tag' => 'tawaran_terkalahkan',
                'title' => 'Tawaran Anda Terkalahkan',
                'subject' => 'Tawaranmu Terkalahkan! 🔔',
                'body' => "Halo {username}!\n\nTawaranmu pada lelang {product_name} telah terkalahkan!\n\nTawaran tertinggi saat ini: Rp {current_price}\nTawaranmu sebelumnya: Rp {your_bid}\n\nSegera pasang tawaran baru jika masih berminat!\n\nLelang berakhir pada: {end_time}",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 11. LELANG DIMENANGKAN (ke Pemenang)
            [
                'code_tag' => 'lelang_dimenangkan',
                'title' => 'Lelang Dimenangkan',
                'subject' => 'Selamat! Kamu Memenangkan Lelang! 🎉',
                'body' => "Halo {username}!\n\nSelamat! Kamu telah memenangkan lelang {product_name}!\n\nHarga akhir: Rp {final_price}\n\nSegera lakukan pembayaran dalam 1x24 jam untuk mengklaim itemmu. Jika tidak, kemenangan akan dibatalkan dan akunmu mungkin dikenakan sanksi.\n\nTerima kasih telah berpartisipasi!",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 12. LELANG BERAKHIR (ke Penjual)
            [
                'code_tag' => 'lelang_berakhir',
                'title' => 'Lelang Berakhir',
                'subject' => 'Lelangmu Telah Berakhir!',
                'body' => "Halo {username}!\n\nLelang untuk produk {product_name} telah berakhir.\n\nHarga akhir: Rp {final_price}\nPemenang: {winner_name}\n\nTunggu pembayaran dari pemenang. Dana akan masuk ke saldo tokomu setelah transaksi selesai.",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 13. LELANG TANPA PENAWAR (ke Penjual)
            [
                'code_tag' => 'lelang_tanpa_penawar',
                'title' => 'Lelang Berakhir Tanpa Penawar',
                'subject' => 'Lelangmu Berakhir Tanpa Penawar',
                'body' => "Halo {username}!\n\nMohon maaf, lelang untuk produk {product_name} telah berakhir tanpa ada penawar.\n\nTips: Coba turunkan harga awal atau promosikan produkmu di media sosial.\n\nKamu bisa membuat lelang baru kapan saja!",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 14. KOMPLAIN DIBUAT (ke Penjual)
            [
                'code_tag' => 'komplain_dibuat',
                'title' => 'Komplain Baru Diterima',
                'subject' => 'Komplain Baru dari Pembeli',
                'body' => "Halo {username}!\n\nAda komplain baru untuk pesanan #{order_id}.\n\nProduk: {product_name}\nKeluhan: {complaint_description}\n\nMohon segera tanggapi komplain ini dalam 2x24 jam untuk menghindari intervensi admin.",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 15. TANGGAPAN KOMPLAIN (ke Pembeli)
            [
                'code_tag' => 'tanggapan_komplain',
                'title' => 'Tanggapan Komplain',
                'subject' => 'Penjual Menanggapi Komplainmu',
                'body' => "Halo {username}!\n\nPenjual telah menanggapi komplainmu untuk pesanan #{order_id}.\n\nTanggapan: {response}\n\nSilakan cek halaman komplain untuk detail lebih lanjut.",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 16. KOMPLAIN DISELESAIKAN (ke Keduanya)
            [
                'code_tag' => 'komplain_diselesaikan',
                'title' => 'Komplain Diselesaikan',
                'subject' => 'Komplain Telah Diselesaikan',
                'body' => "Halo {username}!\n\nKomplain untuk pesanan #{order_id} telah diselesaikan oleh admin.\n\nKeputusan: {resolution}\n\nTerima kasih atas kesabaran dan kerjasamanya.",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 17. ULASAN BARU (ke Penjual)
            [
                'code_tag' => 'ulasan_baru',
                'title' => 'Ulasan Baru Diterima',
                'subject' => 'Ulasan Baru untuk Produkmu! ⭐',
                'body' => "Halo {username}!\n\nAda ulasan baru untuk produk {product_name}!\n\nRating: {rating} ⭐\nKomentar: {comment}\n\nUlasan positif membantu meningkatkan kepercayaan pembeli. Terus berikan pelayanan terbaik!",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 18. AKUN DIBLOKIR
            [
                'code_tag' => 'akun_diblokir',
                'title' => 'Akun Diblokir',
                'subject' => 'Akun Anda Telah Diblokir',
                'body' => "Halo {username}!\n\nDengan berat hati kami informasikan bahwa akunmu telah diblokir karena melanggar Syarat & Ketentuan layanan kami.\n\nJika merasa ini adalah kesalahan, silakan hubungi tim support kami untuk mengajukan banding.",
                'trigger_type' => 'transactional',
                'category' => 'system',
            ],

            // 19. PEMELIHARAAN SISTEM (Broadcast)
            [
                'code_tag' => 'pemeliharaan_sistem',
                'title' => 'Pemberitahuan Pemeliharaan Sistem',
                'subject' => '📢 Informasi Pemeliharaan Sistem',
                'body' => "Halo Juragan!\n\nDemi meningkatkan performa dan keamanan transaksi, akan dilakukan Pemeliharaan Sistem Terjadwal.\n\nSelama maintenance, kamu tidak bisa mengakses aplikasi/website.\n\nMohon selesaikan transaksi tertunda sebelum maintenance dimulai.\n\nSaldo dan data transaksimu dijamin 100% AMAN.\n\nTerima kasih atas pengertiannya!",
                'trigger_type' => 'broadcast',
                'category' => 'system',
            ],

            // 20. PEMBARUAN KETENTUAN (Broadcast)
            [
                'code_tag' => 'pembaruan_ketentuan',
                'title' => 'Pembaruan Syarat & Ketentuan',
                'subject' => 'Penting: Pembaruan Syarat & Ketentuan',
                'body' => "Halo Juragan!\n\nKami telah melakukan pembaruan pada Syarat & Ketentuan layanan kami.\n\nPerubahan akan efektif berlaku minggu depan. Dengan terus menggunakan layanan kami, kamu dianggap telah menyetujui pembaruan ini.\n\nSilakan baca detail lengkapnya di menu Bantuan.\n\nTerima kasih!",
                'trigger_type' => 'broadcast',
                'category' => 'system',
            ],

            // 21. PENGINGAT KEAMANAN (Broadcast)
            [
                'code_tag' => 'pengingat_keamanan',
                'title' => 'Pengingat Keamanan',
                'subject' => 'Tips Keamanan: Jaga Akunmu Tetap Aman! 🛡️',
                'body' => "Waspada Modus Penipuan! 🚨\n\nHalo Juragan!\n\n3 Aturan Emas Keamanan Akun:\n1. Jangan Bagikan OTP/Password kepada siapapun\n2. Transaksi Hanya di Dalam Aplikasi\n3. Cek URL Website sebelum login\n\nAdmin RESMI tidak akan pernah meminta OTP atau password!\n\nStay Safe & Smart!",
                'trigger_type' => 'broadcast',
                'category' => 'system',
            ],
        ];

        foreach ($templates as $tmpl) {
            NotificationTemplate::firstOrCreate(
                ['code_tag' => $tmpl['code_tag']],
                $tmpl
            );
        }
    }
}
