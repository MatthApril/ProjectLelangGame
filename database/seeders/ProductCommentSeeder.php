<?php

namespace Database\Seeders;

use App\Models\ProductComment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comments = [
            [
                'product_id' => 1,
                'user_id' => 4,
                'order_item_id' => 1,
                'comment' => 'Akun ML-nya top banget! Semua hero udah unlocked. Penjual responsif dan prosesnya cepat. Nilai 5 bintang pasti!',
                'rating' => 5,
            ],
            [
                'product_id' => 8,
                'user_id' => 2,
                'order_item_id' => 3,
                'comment' => 'Joki layanan oke, tapi ada sedikit delay waktu pengerjaan. Tapi hasilnya sesuai ekspektasi kok.',
                'rating' => 4,
            ],
            [
                'product_id' => 6,
                'user_id' => 4,
                'order_item_id' => 2,
                'comment' => 'Dua paket joki sekaligus? Sip! Bro bekerja cepet banget, rank push dari epic langsung legend. Rekomendasi banget ke temen.',
                'rating' => 5,
            ],
            [
                'product_id' => 10,
                'user_id' => 3,
                'order_item_id' => 5,
                'comment' => 'Masih pending nih sekarang, nanti kasih rating pas barangnya nyampe dan dicoba.',
                'rating' => 3,
            ],
        ];

        foreach ($comments as $comment) {
            ProductComment::create($comment);
        }
    }
}
