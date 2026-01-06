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
                'unique_id' => 'VERIF-SELLER-001',
                'otp' => '123456',
                'type' => 'register',
                'status' => 'valid',
                'expires_at' => now()->addDays(7),
            ],
            [
                'user_id' => 3,
                'unique_id' => 'VERIF-SELLER-002',
                'otp' => '654321',
                'type' => 'register',
                'status' => 'valid',
                'expires_at' => now()->addDays(7),
            ],
            [
                'user_id' => 4,
                'unique_id' => 'VERIF-USER-001',
                'otp' => '789456',
                'type' => 'register',
                'status' => 'valid',
                'expires_at' => now()->addDays(7),
            ],
            [
                'user_id' => 2,
                'unique_id' => 'VERIF-EMAIL-CHANGE-001',
                'otp' => '321654',
                'type' => 'change_email',
                'status' => 'active',
                'expires_at' => now()->addHours(2),
            ],
            [
                'user_id' => 4,
                'unique_id' => 'VERIF-RESET-PWD-001',
                'otp' => '999888',
                'type' => 'forgot_password',
                'status' => 'active',
                'expires_at' => now()->addHours(1),
            ],
        ];

        foreach ($verifications as $verification) {
            Verification::create($verification);
        }
    }
}
