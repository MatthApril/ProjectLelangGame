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
        $notification = Notification::create([
            'title' => 'Pencairan Dana Dompetku dan Saldo Toko Periode Hari Libur Nasional (Natal dan Tahun Baru)',
            'type' => 'system',
            'message' => <<<EOT
        Hai Juragan!

        Terima kasih telah menggunakan itemku sebagai sarana pemenuhan kebutuhan game dan aplikasi hiburan kamu.

        Kami menginformasikan perihal proses pencairan dana di itemku selama Hari Libur Nasional Peringatan Hari Raya Natal pada tanggal 25 Desember 2025, Cuti Bersama pada tanggal 26 Desember 2025 dan Tahun Baru 2026 Masehi pada tanggal 1 Januari 2026 akan mengalami penyesuaian sebagai berikut:

        1. Untuk pencairan dana pada hari libur tersebut berjalan seperti biasa dan pencairan menggunakan poin toko dinonaktifkan.
        2. Maksimal pencairan dana pada hari libur tersebut adalah sebesar Rp. 500.000.000 (lima ratus juta rupiah) per hari per pengguna.
        3. Permohonan pencairan dana yang melebihi nominal tersebut akan diproses pada hari kerja berikutnya.
        4. Adapun semua transaksi maupun pengembalian dana akan berjalan seperti biasa.

        Jika kamu memiliki pertanyaan lebih lanjut mengenai hal ini, silahkan hubungi Layanan Pengguna itemku. (https://itemku.com/hubungi-kami)

        Happy Trading!
        EOT
        ]);
        
        $users = User::all();

        foreach ($users as $user) {
            NotificationRecipient::create([
                'notification_id' => $notification->notification_id,
                'user_id' => $user->user_id,
            ]);
        }
    }
}
