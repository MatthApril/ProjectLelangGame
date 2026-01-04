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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id('complaint_id');
            $table->foreignId('order_item_id')->constrained('order_items', 'order_item_id')->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->text('description');
            $table->string('proof_img')->nullable();
            $table->enum('status',['waiting_seller','waiting_admin','resolved'])->default('waiting_seller');
            $table->enum('decision', ['refund', 'reject'])->nullable();
            $table->boolean('is_auto_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
