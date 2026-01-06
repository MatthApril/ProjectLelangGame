<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [
            [
                'sender_id' => 2,
                'receiver_id' => 1,
                'content' => 'Halo bos, akun ML gue udah ready. Bisa diambil kapan saja ya?',
            ],
            [
                'sender_id' => 1,
                'receiver_id' => 2,
                'content' => 'Oke siap! Langsung prepare sekarang. Estimasi 1 jam lagi ready.',
            ],
            [
                'sender_id' => 3,
                'receiver_id' => 4,
                'content' => 'Bang, joki grinding genshin ku udah selesai belum? Pengen langsung main hehe',
            ],
            [
                'sender_id' => 4,
                'receiver_id' => 3,
                'content' => 'Sabar bro, baru 50% progress. Artifact domain susah bet ini haha',
            ],
            [
                'sender_id' => 2,
                'receiver_id' => 3,
                'content' => 'Kerjasama kedua toko kita gimana? Biar kompetitor jadi teman aja',
            ],
            [
                'sender_id' => 3,
                'receiver_id' => 2,
                'content' => 'Bisa juga sih, kita diskusi dulu detail sistemnya ya',
            ],
            [
                'sender_id' => 1,
                'receiver_id' => 4,
                'content' => 'Ini akun yang lu beli kemarin status order sudah di update ya',
            ],
            [
                'sender_id' => 4,
                'receiver_id' => 1,
                'content' => 'Makasih bro, pelayanannya top banget! Rating 5 bintang pasti hehe',
            ],
            [
                'sender_id' => 2,
                'receiver_id' => 4,
                'content' => 'Joki account ku dong, push rank dota sampe rank 1000+ LP',
            ],
            [
                'sender_id' => 4,
                'receiver_id' => 2,
                'content' => 'Bisa, tapi perlu waktu 3-4 hari dengan effort max. Budget berapa?',
            ],
            [
                'sender_id' => 3,
                'receiver_id' => 1,
                'content' => 'Komplain order kemarin, item skin tidak sesuai deskripsi',
            ],
            [
                'sender_id' => 1,
                'receiver_id' => 3,
                'content' => 'Waduh sorry bro! Nanti kita lihat bersama-sama dan kasih solusi terbaik',
            ],
            [
                'sender_id' => 4,
                'receiver_id' => 2,
                'content' => 'Akun baru mau dijual? Aku interested buat reseller',
            ],
            [
                'sender_id' => 2,
                'receiver_id' => 4,
                'content' => 'Ada bos, gw punya 3 akun ready stock. Margin reseller bisa nego',
            ],
        ];

        foreach ($messages as $message) {
            Message::create($message);
        }
    }
}
