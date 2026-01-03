<?php

namespace Database\Seeders;

use App\Models\Verification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VerificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Verification::create([
            'user_id' => 2,
            'unique_id' => 'VERIF-0001',
            'otp' => '123456',
            'type' => 'register',
            'expires_at' => now()->addMinutes(2),
            'status' => 'valid'
        ]);

        Verification::create([
            'user_id' => 3,
            'unique_id' => 'VERIF-0002',
            'otp' => '123456',
            'type' => 'register',
            'expires_at' => now()->addMinutes(2),
            'status' => 'valid'
        ]);

        Verification::create([
            'user_id' => 4,
            'unique_id' => 'VERIF-0003',
            'otp' => '123456',
            'type' => 'register',
            'expires_at' => now()->addMinutes(2),
            'status' => 'valid'
        ]);
    }
}
