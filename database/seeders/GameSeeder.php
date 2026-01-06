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
        $games = [
            ['game_name' => 'Mobile Legends', 'game_img' => 'mobile_legends.jpg'],
            ['game_name' => 'Genshin Impact', 'game_img' => 'genshin_impact.jpg'],
            ['game_name' => 'PUBG Mobile', 'game_img' => 'pubg_mobile.jpg'],
            ['game_name' => 'Free Fire', 'game_img' => 'free_fire.jpg'],
            ['game_name' => 'Call of Duty Mobile', 'game_img' => 'cod_mobile.jpg'],
            ['game_name' => 'Clash of Clans', 'game_img' => 'coc.jpg'],
            ['game_name' => 'Dota 2', 'game_img' => 'dota2.jpg'],
            ['game_name' => 'Valorant', 'game_img' => 'valorant.jpg'],
            ['game_name' => 'Minecraft', 'game_img' => 'minecraft.jpg'],
            ['game_name' => 'Elden Ring', 'game_img' => 'elden_ring.jpg'],
            ['game_name' => 'Final Fantasy XIV', 'game_img' => 'ffxiv.jpg'],
            ['game_name' => 'Lost Ark', 'game_img' => 'lost_ark.jpg'],
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }
}
