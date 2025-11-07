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
        Schema::create('order_layanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('montir_id')->constrained('montir')->onDelete('cascade');
            $table->foreignId('layanan_bengkel_id')->constrained('layanan_bengkel')->onDelete('cascade');
            $table->foreignId('pelanggan_id')->constrained('users', 'id')->onDelete('cascade');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->enum('status', ['menunggu', 'kelokasi', 'kerjakan', 'selesai', 'batal'])->default('menunggu');
            $table->integer('harga_layanan')->nullable();
            $table->enum('status_pembayaran', ['belum-lunas', 'lunas'])->default('belum-lunas');
            $table->text('bukti_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_layanan');
    }
};
