<?php

namespace Database\Seeders;

use App\Models\GameCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gameCategories = [
            // Mobile Legends
            ['game_id' => 1, 'category_id' => 1], // Akun
            ['game_id' => 1, 'category_id' => 2], // Joki
            ['game_id' => 1, 'category_id' => 3], // Item
            
            // Genshin Impact
            ['game_id' => 2, 'category_id' => 1],
            ['game_id' => 2, 'category_id' => 2],
            ['game_id' => 2, 'category_id' => 3],
            
            // PUBG Mobile
            ['game_id' => 3, 'category_id' => 1],
            ['game_id' => 3, 'category_id' => 2],
            ['game_id' => 3, 'category_id' => 3],
            
            // Free Fire
            ['game_id' => 4, 'category_id' => 1],
            ['game_id' => 4, 'category_id' => 2],
            ['game_id' => 4, 'category_id' => 3],
            
            // Call of Duty Mobile
            ['game_id' => 5, 'category_id' => 1],
            ['game_id' => 5, 'category_id' => 3],
            
            // Clash of Clans
            ['game_id' => 6, 'category_id' => 1],
            ['game_id' => 6, 'category_id' => 2],
            ['game_id' => 6, 'category_id' => 3],
            
            // Dota 2
            ['game_id' => 7, 'category_id' => 1],
            ['game_id' => 7, 'category_id' => 2],
            ['game_id' => 7, 'category_id' => 3],
            
            // Valorant
            ['game_id' => 8, 'category_id' => 1],
            ['game_id' => 8, 'category_id' => 3],
            
            // Minecraft
            ['game_id' => 9, 'category_id' => 1],
            ['game_id' => 9, 'category_id' => 2],
            
            // Elden Ring
            ['game_id' => 10, 'category_id' => 1],
            ['game_id' => 10, 'category_id' => 3],
            
            // Final Fantasy XIV
            ['game_id' => 11, 'category_id' => 1],
            ['game_id' => 11, 'category_id' => 2],
            ['game_id' => 11, 'category_id' => 3],
            
            // Lost Ark
            ['game_id' => 12, 'category_id' => 1],
            ['game_id' => 12, 'category_id' => 2],
            ['game_id' => 12, 'category_id' => 3],
        ];

        foreach ($gameCategories as $gameCategory) {
            GameCategory::create($gameCategory);
        }
    }
}
