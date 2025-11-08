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
        Schema::table('ulasan_rating', function (Blueprint $table) {
            $table->foreignId('pelanggan_id')->after('order_layanan_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('bengkel_id')->after('pelanggan_id')->constrained('bengkel', 'id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ulasan_rating', function (Blueprint $table) {
            $table->dropForeign(['pelanggan_id']);
            $table->dropColumn('pelanggan_id');
            $table->dropForeign(['bengkel_id']);
            $table->dropColumn('bengkel_id');
        });
    }
};
