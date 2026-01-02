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
            'shop_name' => 'Zinx Store',
            'shop_img' => 'shop_a.jpg',
            'owner_id' => 2,
            'open_hour' => '08:00:00',
            'close_hour' => '22:00:00',
            'status' => 'open',
        ]);

        Shop::create([
            'shop_name' => 'Zinx Store',
            'shop_img' => 'shop_a.jpg',
            'owner_id' => 3,
            'open_hour' => '08:00:00',
            'close_hour' => '22:00:00',
            'status' => 'open',
        ]);
    }
}
