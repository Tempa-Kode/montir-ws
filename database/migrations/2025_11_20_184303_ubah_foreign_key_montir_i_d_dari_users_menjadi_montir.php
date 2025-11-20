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
        Schema::table('ulasan_rating_montir', function (Blueprint $table) {
            $table->dropForeign(['montir_id']);
            $table->foreign('montir_id')->references('id')->on('montir')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ulasan_rating_montir', function (Blueprint $table) {
            $table->dropForeign(['montir_id']);
            $table->foreign('montir_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
