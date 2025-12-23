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
        Schema::create('shops', function (Blueprint $table) {
            $table->id('shop_id');
            $table->string('shop_name');
            $table->foreignId('owner_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->string('shop_img');
            $table->time('open_hour');
            $table->time('close_hour');
            $table->enum('status', ['open', 'closed'])->default('closed');
            $table->integer('running_transactions')->default(0);
            $table->integer('shop_balance')->default(0);
            $table->float('shop_rating')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
