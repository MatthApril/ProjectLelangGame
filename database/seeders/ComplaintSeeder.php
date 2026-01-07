<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\ComplaintResponse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Complaint 1: Produk tidak sesuai deskripsi
        $complaint1 = Complaint::create([
            'order_item_id' => 1,
            'buyer_id' => 4,
            'seller_id' => 2,
            'description' => 'Akun ML yang dibeli tidak memiliki semua hero yang dijanjikan. Hero yang di-list di deskripsi tidak semua ada di akun.',
            'proof_img' => 'storage/app/public/complaint/proof_ml_mismatch.jpg',
            'status' => 'waiting_seller',
            'decision' => null,
            'is_auto_resolved' => false,
            'resolved_at' => null,
        ]);

        ComplaintResponse::create([
            'complaint_id' => $complaint1->complaint_id,
            'message' => 'Mohon maaf atas ketidakjelasan. Saya sudah cek ulang dan memang ada kesalahpahaman. Akan segera kami perbaiki atau refund sesuai kesepakatan.',
            'attachment' => null,
        ]);

        // Complaint 2: Delivery Terlambat
        $complaint2 = Complaint::create([
            'order_item_id' => 2,
            'buyer_id' => 4,
            'seller_id' => 4,
            'description' => 'Joki yang dipesan sudah 2 hari tapi belum ada progress sama sekali. Padahal katanya 24 jam selesai.',
            'proof_img' => null,
            'status' => 'waiting_admin',
            'decision' => null,
            'is_auto_resolved' => false,
            'resolved_at' => null,
        ]);

        ComplaintResponse::create([
            'complaint_id' => $complaint2->complaint_id,
            'message' => 'Gitu ya, maaf bro! Joki saya lagi kepincut main ranked sendiri haha. Tapi janji hari ini harus done. Cek nanti ya 🙏',
            'attachment' => null,
        ]);

        // Complaint 3: Akun Terban / Terdeteksi
        $complaint3 = Complaint::create([
            'order_item_id' => 3,
            'buyer_id' => 2,
            'seller_id' => 1,
            'description' => 'Akun Dota yang saya beli 3 hari kemudian kena banned oleh system. Chat dengan penjual tapi tidak dibalas-balas.',
            'proof_img' => 'storage/app/public/complaint/proof_dota_banned.jpg',
            'status' => 'resolved',
            'decision' => 'refund',
            'is_auto_resolved' => false,
            'resolved_at' => now()->subDays(1),
        ]);

        ComplaintResponse::create([
            'complaint_id' => $complaint3->complaint_id,
            'message' => 'Kami telah memverifikasi dan membuktikan bahwa akun memang digunakan secara curang sebelumnya. Keputusan: REFUND PENUH telah diproses.',
            'attachment' => 'admin_decision_proof.pdf',
        ]);

        // Complaint 4: Item Skin Palsu
        $complaint4 = Complaint::create([
            'order_item_id' => 4,
            'buyer_id' => 3,
            'seller_id' => 2,
            'description' => 'Skin Valorant yang dibeli rupanya skin palsu atau hasil cheat. Tidak bisa digunakan dengan normal di game.',
            'proof_img' => 'storage/app/public/complaint/proof_skin_fake.jpg',
            'status' => 'waiting_seller',
            'decision' => null,
            'is_auto_resolved' => false,
            'resolved_at' => null,
        ]);

        ComplaintResponse::create([
            'complaint_id' => $complaint4->complaint_id,
            'message' => 'Untuk skin Valorant, saya hanya jual kode voucher original dari Riot Games yang 100% aman. Saya pengen lihat bukti skin palsu yang dimaksud pembeli.',
            'attachment' => null,
        ]);
    }
}
