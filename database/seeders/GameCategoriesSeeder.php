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
        GameCategory::create([
            'game_id' => 1,
            'category_id' => 1,
        ]);

        GameCategory::create([
            'game_id' => 1,
            'category_id' => 2,
        ]);

        GameCategory::create([
            'game_id' => 2,
            'category_id' => 2,
        ]);

        GameCategory::create([
            'game_id' => 3,
            'category_id' => 2,
        ]);

        GameCategory::create([
            'game_id' => 3,
            'category_id' => 3,
        ]);
    }
}
