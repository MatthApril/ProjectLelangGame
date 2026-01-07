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

        // Notification 4: Security Alert
        $notif4 = Notification::create([
            'title' => 'Waspada Phishing Scam! 🚨',
            'body' => 'Banyak akun palsu mengatasnamakan admin. Jangan bagikan OTP atau password ke siapapun!',
            'category' => 'system',
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif4->notification_id,
            'user_id' => 2,
            'is_read' => true,
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif4->notification_id,
            'user_id' => 3,
            'is_read' => true,
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif4->notification_id,
            'user_id' => 4,
            'is_read' => true,
        ]);

        // Notification 5: Complaint Status Update
        $notif5 = Notification::create([
            'title' => 'Admin Membalas Komplain Kamu 💬',
            'body' => 'Komplain tentang akun ML kamu telah dikaji oleh tim admin. Lihat detail respons di halaman komplain.',
            'category' => 'order',
        ]);
        NotificationRecipient::create([
            'notification_id' => $notif5->notification_id,
            'user_id' => 4,
            'is_read' => false,
        ]);

    }
}
