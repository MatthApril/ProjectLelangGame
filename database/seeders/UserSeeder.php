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
            'username' => 'matt',
            'email' => 'matthew.aprilian@gmail.com',
            'password' => bcrypt('pass'),
            'role' => 'user'
        ]);
        User::create([
            'username' => 'metiu',
            'email' => 'matthew.a24@mhs.istts.ac.id',
            'password' => bcrypt('pass'),
            'role' => 'user'
        ]);
    }
}
