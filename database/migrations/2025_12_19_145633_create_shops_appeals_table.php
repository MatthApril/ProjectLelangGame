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
        Schema::create('shops_appeals', function (Blueprint $table) {
            $table->id('appeal_id');
            $table->foreignId('appeal_shop_id')->constrained('shops', 'shop_id')->cascadeOnDelete();
            $table->text('justification');
            $table->boolean('is_approved')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops_appeals');
    }
};
