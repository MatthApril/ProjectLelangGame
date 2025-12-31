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
        Schema::create('products_comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->foreignId('product_id')->constrained('products', 'product_id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users','user_id')->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items','order_item_id')->cascadeOnDelete();
            $table->text('comment')->nullable();
            $table->integer('rating');
            $table->timestamp('created_at')->useCurrent();
            $table->unique('order_item_id');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_comments');
    }
};
