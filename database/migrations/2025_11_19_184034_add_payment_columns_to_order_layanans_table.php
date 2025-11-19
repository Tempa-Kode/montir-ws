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
            $table->string('kode_order', 25)->nullable()->after('id');
            $table->string('snap_token')->nullable()->after('status');
            $table->enum('status_pembayaran', ['pending', 'paid', 'expired', 'failed'])
                ->default('pending')
                ->after('snap_token')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_layanan', function (Blueprint $table) {
            $table->dropColumn(['kode_order','snap_token']);
            $table->enum('status_pembayaran', ['belum-lunas','lunas'])->default('belum-lunas')->change();
        });
    }
};
