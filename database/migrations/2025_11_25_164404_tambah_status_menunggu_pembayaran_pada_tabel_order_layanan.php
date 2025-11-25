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
        Schema::table('order_layanan', function (Blueprint $table) {
            $table->enum('status', ['menunggu','kelokasi','kerjakan', 'pembayaran', 'selesai','batal'])
                ->default('menunggu')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_layanan', function (Blueprint $table) {
            $table->enum('status', ['menunggu','kelokasi','kerjakan', 'selesai','batal'])
                ->default('menunggu')
                ->change();
        });
    }
};
