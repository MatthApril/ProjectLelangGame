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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->string('order_id', 255);
            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->foreignId('shop_id')->constrained('shops', 'shop_id');
            $table->integer('product_price');
            $table->integer('quantity');
            $table->integer('subtotal');
            $table->enum('status', ['pending', 'paid', 'shipped', 'completed', 'cancelled']);
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
