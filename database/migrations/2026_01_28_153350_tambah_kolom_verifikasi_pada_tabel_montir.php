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
        Schema::table('montir', function (Blueprint $table) {
            $table->boolean('verifikasi')->default(false)->after('bengkel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('montir', function (Blueprint $table) {
            $table->dropColumn('verifikasi');
        });
    }
};
