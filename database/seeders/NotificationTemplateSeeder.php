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
            // 1. WELCOME NEW USER
            [
                'code_tag' => 'welcome_user',
                'title' => 'Welcome Message',
                'subject' => 'Selamat Datang! Mulai Petualangan dan Menangkan Lelang Pertamamu',
                'body' => <<<EOT
            Hai {username}!

            Selamat datang di keluarga besar kami! Terima kasih telah mempercayakan kebutuhan gaming kamu di sini.

            Akun kamu telah berhasil dibuat dan sekarang kamu siap untuk menjelajahi ribuan produk game dan layanan digital termurah, aman, dan terpercaya.

            🔥 **Fitur Unggulan: Balai Lelang (Auction House)**
            Jangan lewatkan keseruan di fitur Lelang kami! Di sini kamu bisa:
            1. Mendapatkan item langka atau akun sultan dengan harga miring.
            2. Melakukan penawaran (bid) secara real-time melawan pengguna lain.
            3. Menjual item koleksimu dengan sistem lelang untuk keuntungan maksimal.

            Tunggu apa lagi? Isi saldo dompetmu sekarang, cari item impianmu, dan jadilah pemenang lelang hari ini!

            Jika butuh bantuan, tim CS kami siap membantu 24/7.

            Happy Trading & Bidding!
            EOT,
                'trigger_type' => 'transactional',
                'category' => 'system',
            ],

            // 2. CONGRATULATIONS NEW SELLER
            [
                'code_tag' => 'welcome_seller',
                'title' => 'New Seller Welcome',
                'subject' => 'Selamat! Toko Kamu Resmi Dibuka 🎉',
                'body' => <<<EOT
            Halo {username}!

            Selamat! Kamu telah resmi terdaftar sebagai penjual. Langkah pertama menuju kesuksesan bisnismu dimulai hari ini.

            Kini kamu bisa mulai mengunggah produk, mengatur etalase, dan menjangkau ribuan gamers yang siap membeli produkmu.

            💡 **Tips Sukses Berjualan:**
            1. **Foto Produk Menarik:** Gunakan gambar yang jelas dan beresolusi tinggi.
            2. **Deskripsi Lengkap:** Jelaskan detail produkmu dengan jujur dan rinci.
            3. **Respon Cepat:** Balas chat pembeli secepat mungkin untuk meningkatkan reputasi toko.
            4. **Cek Stok Berkala:** Pastikan stok produkmu selalu update.

            Ingat, kepuasan pelanggan adalah kunci utama. Jaga integritas dan berikan pelayanan terbaikmu.

            Semoga laris manis!
            EOT,
                'trigger_type' => 'transactional',
                'category' => 'system',
            ],

            // 3. ORDER PAID
            [
                'code_tag' => 'order_success',
                'title' => 'Order Payment Success',
                'subject' => 'Pembayaran Berhasil #{order_id}',
                'body' => "Halo {username}, pembayaranmu sebesar Rp {amount} telah diterima.",
                'trigger_type' => 'transactional',
                'category' => 'order',
            ],

            // 5. NEW YEAR PROMO
            [
                'code_tag' => 'new_year_2026',
                'title' => 'New Year 2026 Greeting',
                'subject' => 'Happy New Year 2026! Siap Level Up Bareng Kami? 🚀',
                'body' => <<<EOT
            Hai Juragan!

            Selamat Tahun Baru 2026! 🎉

            Tak terasa 365 hari di tahun 2025 telah kita lewati bersama. Terima kasih karena kamu telah menjadikan platform ini sebagai rumah untuk setiap transaksi, hobi, dan bisnis digitalmu.

            Tahun 2025 adalah tahun yang luar biasa. Kita telah menyaksikan ribuan lelang sengit, jutaan transaksi aman, dan komunitas yang semakin solid. Entah kamu seorang **Buyer** yang sedang melengkapi koleksi skin impian, atau seorang **Seller** yang sedang membangun kerajaan bisnis digital, kamu adalah MVP kami sesungguhnya.

            **Apa yang menantimu di 2026?**
            Tahun baru berarti semangat baru dan fitur baru! Kami berkomitmen untuk menghadirkan:
            1. **Sistem Lelang yang Lebih Seru:** Lebih banyak event lelang dengan item langka.
            2. **Keamanan Transaksi Prioritas:** Perlindungan lebih ketat untuk kenyamanan belanja dan jualanmu.
            3. **Komunitas yang Lebih Hidup:** Turnamen dan event berhadiah menarik.

            Mari kita buka lembaran baru ini dengan semangat positif. Kejar rank impianmu, temukan item idamanmu, dan raih cuan maksimalmu di tahun ini.

            Tetap jaga kesehatan dan semangat gaming-mu. Jika kamu butuh bantuan selama liburan tahun baru ini, Tim Support kami tetap siap sedia membantumu.

            Let's Make 2026 Legendary!

            Salam Hangat,
            Tim LelangGame
            EOT,
                'trigger_type' => 'broadcast',
                'category' => 'promo',
            ],

            // 6. SYSTEM MAINTENANCE
            [
                'code_tag' => 'system_maintenance_alert',
                'title' => 'System Maintenance Alert',
                'subject' => '📢 Informasi Pemeliharaan Sistem (Maintenance)',
                'body' => <<<EOT
            Halo Juragan!

            Demi meningkatkan performa dan keamanan transaksi di platform LelangGame, kami informasikan bahwa akan dilakukan **Pemeliharaan Sistem Terjadwal** pada server kami.

            ⚠️ **Dampak Layanan:**
            Selama periode maintenance, kamu tidak akan bisa mengakses aplikasi/website, termasuk melakukan Top Up, Penarikan Dana (Withdrawal), ataupun Bid di Balai Lelang.

            Mohon selesaikan transaksi atau pembayaran yang tertunda sebelum waktu maintenance dimulai untuk menghindari kendala teknis.

            Tenang saja! Saldo Dompetku dan data transaksimu dijamin 100% AMAN. Sistem akan kembali normal segera setelah proses pemeliharaan selesai.

            Terima kasih atas pengertian dan kesabarannya. Kami sedang bekerja keras agar pengalaman trading kamu makin ngebut! 🚀

            Salam,
            Tim Teknis
            EOT,
                'trigger_type' => 'broadcast',
                'category' => 'system',
            ],

            // 7. TERMS OF SERVICE UPDATE
            [
                'code_tag' => 'tos_update_2025',
                'title' => 'Terms of Service Update',
                'subject' => 'Penting: Pembaruan Syarat & Ketentuan Layanan',
                'body' => <<<EOT
            Hai Juragan,

            Sebagai komitmen kami untuk menciptakan ekosistem jual-beli yang lebih transparan, adil, dan aman, kami telah melakukan pembaruan pada **Syarat & Ketentuan (Terms of Service)** serta **Kebijakan Privasi** kami.

            Perubahan ini mencakup poin-poin penting mengenai:
            1. **Keamanan Akun:** Kewajiban penggunaan 2FA untuk penjual.
            2. **Aturan Lelang:** Sanksi tegas bagi "Bid & Run" (pemenang lelang yang kabur).
            3. **Biaya Layanan:** Penyesuaian struktur biaya admin untuk kategori tertentu.

            Perubahan ini akan efektif berlaku mulai minggu depan. Dengan terus menggunakan layanan kami setelah tanggal tersebut, kamu dianggap telah menyetujui pembaruan ini.

            Kami sangat menyarankan kamu untuk meluangkan waktu sejenak membaca detail lengkapnya pada menu "Bantuan" atau link di footer website.

            Terima kasih telah menjadi bagian dari komunitas yang suportif!

            Salam,
            Tim Legal
            EOT,
                'trigger_type' => 'broadcast',
                'category' => 'system',
            ],

            // 8. APOLOGY / COMPENSATION
            [
                'code_tag' => 'server_apology_gift',
                'title' => 'Server Issue Apology',
                'subject' => 'Mohon Maaf Atas Gangguan Teknis + Kompensasi 🎁',
                'body' => <<<EOT
            Halo Juragan!

            Kami memohon maaf yang sebesar-besarnya atas gangguan akses yang terjadi pada aplikasi pagi ini. Kami menyadari hal ini telah mengganggu aktivitas jual beli dan keasikan nge-bid kamu.

            Tim teknis kami telah memperbaiki masalah tersebut dan saat ini sistem sudah berjalan normal kembali dengan stabilitas penuh.

            🙏 **Sebagai Permintaan Maaf:**
            Kami telah mengirimkan **Voucher Diskon Biaya Layanan** ke akun kamu yang bisa digunakan untuk transaksi berikutnya. Silakan cek menu "Voucher Saya".

            Kami akan terus mengevaluasi infrastruktur kami agar kejadian serupa tidak terulang. Terima kasih karena tetap setia bersama kami!

            Happy Trading!
            EOT,
                'trigger_type' => 'broadcast',
                'category' => 'promo',
            ],

            // 9. SECURITY REMINDER
            [
                'code_tag' => 'security_reminder_general',
                'title' => 'Security Reminder',
                'subject' => 'Tips Keamanan: Jaga Akun Sultanmu Tetap Aman! 🛡️',
                'body' => <<<EOT
            Waspada Modus Penipuan! 🚨

            Halo Juragan, kami mendeteksi maraknya upaya "Phishing" yang mengatasnamakan admin LelangGame di sosial media.

            Mohon ingat 3 Aturan Emas Keamanan Akun ini:

            1. **Jangan Bagikan OTP/Password:** Admin RESMI tidak akan pernah meminta kode OTP atau password kamu dengan alasan apapun.
            2. **Transaksi Hanya di Dalam Aplikasi:** Jangan mau diajak transaksi langsung (Direct Transfer) ke rekening pribadi penjual. Dana kamu tidak terlindungi jika transaksi dilakukan di luar sistem kami.
            3. **Cek URL Website:** Pastikan kamu hanya login di website resmi kami. Jangan klik link mencurigakan dari WhatsApp atau DM Instagram.

            Jika menemukan aktivitas mencurigakan, segera laporkan ke CS kami. Mari saling jaga agar komunitas kita bebas dari scammer!

            Stay Safe & Smart!
            EOT,
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
