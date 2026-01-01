<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id('notif_log_id');
            $table->foreignId('notif_temp_id')->constrained('notification_templates', 'notif_temp_id')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('users', 'user_id')->cascadeOnDelete(); // Who pressed the button?
            $table->string('target_audience'); // e.g., "All Sellers", "Buyers > Level 5"
            $table->integer('recipients_count'); // Snapshot of how many people got it (e.g., 500)
            $table->timestamp('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
