<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Game::create([
            'game_name' => 'Game A',
            'game_img' => 'game_a.jpg',
        ]);

        Game::create([
            'game_name' => 'Game B',
            'game_img' => 'game_b.jpg',
        ]);

        Game::create([
            'game_name' => 'Game C',
            'game_img' => 'game_c.jpg',
        ]);
    }
}
