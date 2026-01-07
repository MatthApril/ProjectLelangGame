<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Mockery\Matcher\Not;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            NotificationTemplateSeeder::class,
            VerificationSeeder::class,
            GameSeeder::class,
            CategorySeeder::class,
            GameCategoriesSeeder::class,
            ShopSeeder::class,
            ProductSeeder::class,
            CartSeeder::class,
            MessageSeeder::class,
            OrderSeeder::class,
            ProductCommentSeeder::class,
            ComplaintSeeder::class,
            NotificationSeeder::class,
            NotificationLogSeeder::class,
            AuctionSeeder::class,
            AdminSettingSeeder::class,
        ]);
    }
}
