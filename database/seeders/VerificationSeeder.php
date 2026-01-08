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
        $verifications = [
            [
                'user_id' => 2,
                'unique_id' => 'VERIF-USER-001',
                'otp' => '123456',
                'type' => 'register',
                'status' => 'valid',
                'expires_at' => now()->addDays(7),
            ],
            [
                'user_id' => 3,
                'unique_id' => 'VERIF-USER-002',
                'otp' => '654321',
                'type' => 'register',
                'status' => 'valid',
                'expires_at' => now()->addDays(7),
            ],
            [
                'user_id' => 4,
                'unique_id' => 'VERIF-USER-003',
                'otp' => '789456',
                'type' => 'register',
                'status' => 'valid',
                'expires_at' => now()->addDays(7),
            ],
            [
                'user_id' => 5,
                'unique_id' => 'VERIF-USER-004',
                'otp' => '789456',
                'type' => 'register',
                'status' => 'valid',
                'expires_at' => now()->addDays(7),
            ],
            [
                'user_id' => 6,
                'unique_id' => 'VERIF-USER-005',
                'otp' => '789456',
                'type' => 'register',
                'status' => 'valid',
                'expires_at' => now()->addDays(7),
            ],
            [
                'user_id' => 7,
                'unique_id' => 'VERIF-USER-006',
                'otp' => '789456',
                'type' => 'register',
                'status' => 'valid',
                'expires_at' => now()->addDays(7),
            ],
        ];

        foreach ($verifications as $verification) {
            Verification::create($verification);
        }
    }
}
