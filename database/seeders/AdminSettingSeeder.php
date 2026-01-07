<?php

namespace Database\Seeders;

use App\Models\AdminSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminSettings::create([
            'platform_fee_percentage' => 7.0,
        ]);
    }
}
