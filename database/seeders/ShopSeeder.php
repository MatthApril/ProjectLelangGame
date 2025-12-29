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
        Shop::create([
            'shop_name' => 'Gamer Haven',
            'owner_id' => 2,
            'shop_img' => 'stoage/images/shops/shop1.png',
            'open_hour' => '09:00:00',
            'close_hour' => '21:00:00',
            'status' => 'open',
        ]);
    }
}
