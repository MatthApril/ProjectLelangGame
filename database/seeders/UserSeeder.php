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
            'username' => 'test',
            'email' => 'testinggdg12345@gmail.com',
            'password' => bcrypt('pass'),
            'role' => 'admin'
        ]);
        User::create([
            'username' => 'owner1',
            'email' => 'owner1@example.com',
            'password' => bcrypt('pass'),
            'role' => 'seller'
        ]);
        User::create([
            'username' => 'owner2',
            'email' => 'owner2@example.com',
            'password' => bcrypt('pass'),
            'role' => 'seller'
        ]);
    }
}
