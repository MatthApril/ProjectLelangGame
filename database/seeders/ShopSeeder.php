<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shops = [
            [
                'shop_name' => 'Zinx Gaming Store',
                'shop_img' => '/shops/zinx_store.jpg',
                'owner_id' => 2,
                'open_hour' => '08:00:00',
                'close_hour' => '22:00:00',
                'status' => 'open',
                'running_transactions' => 0,
                'shop_balance' => 550000,
                'shop_rating' => 4.8,
            ],
            [
                'shop_name' => 'Test Gaming Hub',
                'shop_img' => '/shops/test_store.jpg',
                'owner_id' => 3,
                'open_hour' => '09:00:00',
                'close_hour' => '23:00:00',
                'status' => 'open',
                'running_transactions' => 0,
                'shop_balance' => 0,
                'shop_rating' => 4.6,
            ],
            [
                'shop_name' => 'Akun Gaming Gampang',
                'shop_img' => '/shops/akun_gampang.jpg',
                'owner_id' => 5,
                'open_hour' => '07:00:00',
                'close_hour' => '23:59:00',
                'status' => 'open',
                'running_transactions' => 0,
                'shop_balance' => 0,
                'shop_rating' => 4.9,
            ],
            [
                'shop_name' => 'Joki Pro Indonesia',
                'shop_img' => '/shops/joki_pro.jpg',
                'owner_id' => 6,
                'open_hour' => '10:00:00',
                'close_hour' => '22:00:00',
                'status' => 'open',
                'running_transactions' => 700000,
                'shop_balance' => 0,
                'shop_rating' => 4.7,
            ],
            [
                'shop_name' => 'Item Langka Store',
                'shop_img' => '/shops/item_langka.jpg',
                'owner_id' => 7,
                'open_hour' => '08:30:00',
                'close_hour' => '21:00:00',
                'status' => 'open',
                'running_transactions' => 0,
                'shop_balance' => 0,
                'shop_rating' => 4.5,
            ],
        ];

        foreach ($shops as $shop) {
            Shop::create($shop);
        }
    }
}
