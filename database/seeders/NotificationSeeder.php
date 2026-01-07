<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\NotificationRecipient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Mockery\Matcher\Not;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Notification 1: Order Confirmed
        $notif1 = Notification::create([
            'title' => 'Pesanan Berhasil Dibayar! ✅',
            'body' => 'Terima kasih! Pembayaran Rp 250.000 untuk akun ML Legend telah kami terima. Penjual akan segera mengirim akun ke email kamu.',
            'category' => 'order',
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif1->notification_id,
            'user_id' => 4,
            'is_read' => true,
        ]);

        // Notification 2: Item Shipped
        $notif2 = Notification::create([
            'title' => 'Pesanan Dikirim 📦',
            'body' => 'Pesanan kamu telah dikirim oleh penjual. Link akun sudah dikirim ke email registrasi kamu.',
            'category' => 'order',
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif2->notification_id,
            'user_id' => 4,
            'is_read' => true,
        ]);

        // Notification 3: New Seller Greeting
        $notif3 = Notification::create([
            'title' => 'Selamat! Toko Kamu Sudah Aktif 🎉',
            'body' => 'Toko gaming kamu telah berhasil dibuat. Mulai upload produk dan raih penjualan pertamamu!',
            'category' => 'system',
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif3->notification_id,
            'user_id' => 2,
            'is_read' => true,
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif3->notification_id,
            'user_id' => 3,
            'is_read' => true,
        ]);

        // Notification 4: New Year Promo Reminder
        $notif4 = Notification::create([
            'title' => 'Happy New Year 2026! 🎆',
            'body' => 'Tahun baru sudah tiba! Dapatkan banyak flash sale dan promo menarik di LelangGame. Jangan sampai terlewatkan!',
            'category' => 'promo',
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif4->notification_id,
            'user_id' => 2,
            'is_read' => false,
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif4->notification_id,
            'user_id' => 3,
            'is_read' => false,
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif4->notification_id,
            'user_id' => 4,
            'is_read' => false,
        ]);

        // Notification 5: Security Alert
        $notif5 = Notification::create([
            'title' => 'Waspada Phishing Scam! 🚨',
            'body' => 'Banyak akun palsu mengatasnamakan admin. Jangan bagikan OTP atau password ke siapapun!',
            'category' => 'system',
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif5->notification_id,
            'user_id' => 2,
            'is_read' => true,
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif5->notification_id,
            'user_id' => 3,
            'is_read' => true,
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif5->notification_id,
            'user_id' => 4,
            'is_read' => true,
        ]);

        // Notification 6: Auction Starting Soon
        $notif6 = Notification::create([
            'title' => 'Lelang Dimulai dalam 1 Jam! ⏰',
            'body' => 'Akun Mobile Legends Collector Limited akan segera dibuka untuk lelang. Siapkan dompet kamu!',
            'category' => 'promo',
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif6->notification_id,
            'user_id' => 4,
            'is_read' => false,
        ]);

        // Notification 7: Complaint Status Update
        $notif7 = Notification::create([
            'title' => 'Admin Membalas Komplain Kamu 💬',
            'body' => 'Komplain tentang akun ML kamu telah dikaji oleh tim admin. Lihat detail respons di halaman komplain.',
            'category' => 'order',
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif7->notification_id,
            'user_id' => 4,
            'is_read' => false,
        ]);

        // Notification 8: New Joki Available
        $notif8 = Notification::create([
            'title' => 'Joki Pro Baru Tersedia! 🎮',
            'body' => 'Pro player Dota 2 immortal baru bergabung! Tawaran joki grind mereka lebih cepat dari yang lain.',
            'category' => 'promo',
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif8->notification_id,
            'user_id' => 2,
            'is_read' => false,
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif8->notification_id,
            'user_id' => 3,
            'is_read' => false,
        ]);

        // Notification 9: Flash Sale Alert
        $notif9 = Notification::create([
            'title' => 'Flash Sale Skin Legendary - 40% OFF! 🔥',
            'body' => 'Dapatkan skin legendary Mobile Legends dengan harga promo hari ini. Stok terbatas!',
            'category' => 'promo',
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif9->notification_id,
            'user_id' => 4,
            'is_read' => false,
        ]);
    }
}
