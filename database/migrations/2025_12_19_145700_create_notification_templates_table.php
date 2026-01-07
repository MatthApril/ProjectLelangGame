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
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id('notif_temp_id');
            $table->string('code_tag')->unique(); // e.g., 'welcome', 'auction_win' (Programmers use this)
            $table->string('title'); // Admin-friendly name e.g., "Welcome Message"
            $table->string('subject');
            $table->text('body'); // HTML content with variables like {username}
            $table->enum('trigger_type', ['transactional', 'broadcast']); // The logic controller
            $table->enum('category', ['system', 'order']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
