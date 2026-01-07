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
            ['game_name' => 'Mobile Legends', 'game_img' => 'storage/app/public/games/mobile_legends.jpg'],
            ['game_name' => 'Genshin Impact', 'game_img' => 'storage/app/public/games/genshin_impact.jpg'],
            ['game_name' => 'PUBG Mobile', 'game_img' => 'storage/app/public/games/pubg_mobile.jpg'],
            ['game_name' => 'Free Fire', 'game_img' => 'storage/app/public/games/free_fire.jpg'],
            ['game_name' => 'Call of Duty Mobile', 'game_img' => 'storage/app/public/games/cod_mobile.jpg'],
            ['game_name' => 'Clash of Clans', 'game_img' => 'storage/app/public/games/coc.jpg'],
            ['game_name' => 'Dota 2', 'game_img' => 'storage/app/public/games/dota2.jpg'],
            ['game_name' => 'Valorant', 'game_img' => 'storage/app/public/games/valorant.jpg'],
            ['game_name' => 'Minecraft', 'game_img' => 'storage/app/public/games/minecraft.jpg'],
            ['game_name' => 'Elden Ring', 'game_img' => 'storage/app/public/games/elden_ring.jpg'],
            ['game_name' => 'Final Fantasy XIV', 'game_img' => 'storage/app/public/games/ffxiv.jpg'],
            ['game_name' => 'Lost Ark', 'game_img' => 'storage/app/public/games/lost_ark.jpg'],
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }
}
