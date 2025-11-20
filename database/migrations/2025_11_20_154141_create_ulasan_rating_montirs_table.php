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
        Schema::create('ulasan_rating_montir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_layanan_id')->constrained('order_layanan')->onDelete('cascade');
            $table->foreignId('pelanggan_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('montir_id')->constrained('users', 'id')->onDelete('cascade');
            $table->integer('rating')->min(1)->max(5);
            $table->text('ulasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan_rating_montir');
    }
};
