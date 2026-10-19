<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove package system from lowongan.
     */
    public function up(): void
    {
        Schema::table('lowongan_perusahaans', function (Blueprint $table) {
            $table->dropForeign(['paket_id']);
            $table->dropColumn(['paket_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lowongan_perusahaans', function (Blueprint $table) {
            $table->foreignId('paket_id')->nullable()->constrained('paket_lowongans')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
