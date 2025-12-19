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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id('account_id'); // AUTO INCREMEMT
            $table->string('username')->unique();
            $table->string('password');
            $table->string('email')->unique();
            $table->enum('role', ['seller', 'user', 'admin'])->default('user');
            $table->timestamps(); // langsung bikin created_at dan updated_at timestamp buat yang kolom baru kalau ada s nya ini
            $table->softDeletes(); // untuk deleted_at atau soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
