<?php

namespace Database\Seeders;

use App\Models\NotificationLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logs = [
            [
                'notif_temp_id' => 1, // welcome_user
                'admin_id' => 1,
                'target_audience' => 'All New Users',
                'recipients_count' => 1,
                'sent_at' => now()->subDays(5),
            ],
            [
                'notif_temp_id' => 2, // welcome_seller
                'admin_id' => 1,
                'target_audience' => 'All New Sellers',
                'recipients_count' => 2,
                'sent_at' => now()->subDays(4),
            ],
            [
                'notif_temp_id' => 5, // new_year_2026
                'admin_id' => 1,
                'target_audience' => 'All Users',
                'recipients_count' => 4,
                'sent_at' => now()->subHours(12),
            ],
            [
                'notif_temp_id' => 6, // system_maintenance_alert
                'admin_id' => 1,
                'target_audience' => 'All Users',
                'recipients_count' => 4,
                'sent_at' => now()->subHours(4),
            ],
            [
                'notif_temp_id' => 8, // security_reminder_general
                'admin_id' => 1,
                'target_audience' => 'All Users',
                'recipients_count' => 4,
                'sent_at' => now()->subDays(1),
            ],
        ];

        foreach ($logs as $log) {
            NotificationLog::create($log);
        }
    }
}
