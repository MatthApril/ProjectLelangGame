<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'lelanggameofficial',
            'email' => 'lelanggameofficial@gmail.com',
            'password' => bcrypt('pass'),
            'role' => 'admin'
        ]);

        User::create([
            'username' => 'zinx',
            'email' => 'zinx8729@gmail.com',
            'password' => bcrypt('pass'),
            'role' => 'seller'
        ]);

        User::create([
            'username' => 'test',
            'email' => 'testinggdg12345@gmail.com',
            'password' => bcrypt('pass'),
            'role' => 'seller'
        ]);

        User::create([
            'username' => 'han',
            'email' => 'hanchandra14@gmail.com',
            'password' => bcrypt('pass'),
            'role' => 'user'
        ]);

    }
}
