<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Akun Products
            [
                'shop_id' => 1,
                'product_name' => 'Akun Mobile Legends Mythic Rank',
                'description' => 'Akun ML Mythic udah punya banyak hero langka. Skin epic collector item. Fast delivery, dijamin aman.',
                'product_img' => '/products/ml_account_mythic.jpg',
                'stok' => 5,
                'price' => 250000,
                'type' => 'normal',
                'category_id' => 1,
                'game_id' => 1,
            ],
            [
                'shop_id' => 1,
                'product_name' => 'Akun Genshin Impact AR 50+',
                'description' => 'Akun Genshin punya 5 star lengkap. Ayaka, Ganyu, Hu Tao semua ada. Original email bisa diganti langsung.',
                'product_img' => '/products/genshin_ar50.jpg',
                'stok' => 3,
                'price' => 400000,
                'type' => 'normal',
                'category_id' => 1,
                'game_id' => 2,
            ],
            [
                'shop_id' => 2,
                'product_name' => 'Akun PUBG Mobile Legend Tier',
                'description' => 'PUBG Mobile akun udah legend tier, punya limited skins. Full supply ammo. Original server.',
                'product_img' => '/products/pubg_legend.jpg',
                'stok' => 7,
                'price' => 200000,
                'type' => 'normal',
                'category_id' => 1,
                'game_id' => 3,
            ],
            [
                'shop_id' => 3,
                'product_name' => 'Akun Free Fire Diamond Rank',
                'description' => 'FF akun punya banyak karakter rare. Diamond pass aktif 3 bulan. Best deal ever!',
                'product_img' => '/products/ff_diamond.jpg',
                'stok' => 10,
                'price' => 150000,
                'type' => 'normal',
                'category_id' => 1,
                'game_id' => 4,
            ],
            [
                'shop_id' => 2,
                'product_name' => 'Akun Dota 2 Immortal',
                'description' => 'Dota 2 akun immortal rank 1500+. Punya rare arcana items. Clean history, tidak pernah ban.',
                'product_img' => '/products/dota2_immortal.jpg',
                'stok' => 2,
                'price' => 500000,
                'type' => 'auction',
                'category_id' => 1,
                'game_id' => 7,
            ],
            // Joki Products
            [
                'shop_id' => 4,
                'product_name' => 'Joki Rank Mobile Legends Pro Player',
                'description' => 'Joki push rank ML dari epic ke mythic. Pro player berpengalaman 5+ tahun. Cepat dan aman.',
                'product_img' => '/products/joki_ml.jpg',
                'stok' => 15,
                'price' => 350000,
                'type' => 'normal',
                'category_id' => 2,
                'game_id' => 1,
            ],
            [
                'shop_id' => 4,
                'product_name' => 'Joki Grinding Battle Pass Genshin',
                'description' => 'Joki farming material domains Genshin. Grind artifact domain untuk equipment bagus. Profesional.',
                'product_img' => '/products/joki_genshin.jpg',
                'stok' => 8,
                'price' => 300000,
                'type' => 'normal',
                'category_id' => 2,
                'game_id' => 2,
            ],
            [
                'shop_id' => 5,
                'product_name' => 'Joki Leveling Dota 2',
                'description' => 'Joki level up akun Dota dari 0. Farming LP cepat. Expertise div 1 player.',
                'product_img' => '/products/joki_dota.jpg',
                'stok' => 6,
                'price' => 450000,
                'type' => 'normal',
                'category_id' => 2,
                'game_id' => 7,
            ],
            [
                'shop_id' => 1,
                'product_name' => 'Joki Quest Minecraft Survival',
                'description' => 'Joki complete quest dan achievement Minecraft. Build base cantik. Super cepat selesai.',
                'product_img' => '/products/joki_minecraft.jpg',
                'stok' => 12,
                'price' => 200000,
                'type' => 'normal',
                'category_id' => 2,
                'game_id' => 9,
            ],
            // Item Products
            [
                'shop_id' => 5,
                'product_name' => 'Skin Legendary Mobile Legends Bundle',
                'description' => '5 skin legendary ML high quality. Collector item yang rare. Harga special untuk paket.',
                'product_img' => '/products/ml_skins.jpg',
                'stok' => 20,
                'price' => 180000,
                'type' => 'normal',
                'category_id' => 3,
                'game_id' => 1,
            ],
            [
                'shop_id' => 3,
                'product_name' => 'Weapon Skin Valorant Premium Pack',
                'description' => 'Valorant senjata skin premium vandal, phantom, classic. Condition mint. Harga grosir.',
                'product_img' => '/products/valorant_skins.jpg',
                'stok' => 10,
                'price' => 350000,
                'type' => 'auction',
                'category_id' => 3,
                'game_id' => 8,
            ],
            [
                'shop_id' => 2,
                'product_name' => 'Chest Icon Free Fire Bundle',
                'description' => 'FF chest berisi skin M1014 legendary dan character skin rare. Terbatas stok.',
                'product_img' => '/products/ff_chest.jpg',
                'stok' => 25,
                'price' => 120000,
                'type' => 'normal',
                'category_id' => 3,
                'game_id' => 4,
            ],
            [
                'shop_id' => 1,
                'product_name' => 'Mount Pet Clash of Clans Ultimate',
                'description' => 'CoC mount hewan langka dan pet unik. Limited edition yang udah jarang diproduksi.',
                'product_img' => '/products/coc_mount.jpg',
                'stok' => 8,
                'price' => 280000,
                'type' => 'auction',
                'category_id' => 3,
                'game_id' => 6,
            ],
            [
                'shop_id' => 4,
                'product_name' => 'Character Outfit Elden Ring Cosmetic',
                'description' => 'Elden Ring outfit exclusive dan gauntlet rare. Bikin character tambah bagus maksimal.',
                'product_img' => '/products/elden_outfit.jpg',
                'stok' => 6,
                'price' => 320000,
                'type' => 'auction',
                'category_id' => 3,
                'game_id' => 10,
            ],
            // Auction Items
            [
                'shop_id' => 5,
                'product_name' => 'Akun Mobile Legends Collector Limited',
                'description' => 'Akun ML super langka punya semua skin exclusive event. Full hero mythic ready. Dijual auction eksklusif!',
                'product_img' => '/products/ml_collector.jpg',
                'stok' => 1,
                'price' => 1500000,
                'type' => 'auction',
                'category_id' => 1,
                'game_id' => 1,
            ],
            [
                'shop_id' => 1,
                'product_name' => 'Akun Genshin Impact Full 5 Star',
                'description' => 'Genshin akun punya semua 5 star character ever released. Whale account sekali jalan.',
                'product_img' => '/products/genshin_full.jpg',
                'stok' => 1,
                'price' => 2000000,
                'type' => 'normal',
                'category_id' => 1,
                'game_id' => 2,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
