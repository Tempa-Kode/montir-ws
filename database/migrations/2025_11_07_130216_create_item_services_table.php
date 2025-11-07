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
        Schema::create('item_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_layanan_id')->constrained('order_layanan')->onDelete('cascade');
            $table->string('nama_item', 100);
            $table->integer('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_service');
    }
};
