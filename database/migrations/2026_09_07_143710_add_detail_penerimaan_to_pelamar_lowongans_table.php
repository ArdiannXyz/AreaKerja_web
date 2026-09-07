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
        Schema::table('pelamar_lowongans', function (Blueprint $table) {
            $table->string('jadwal')->nullable()->after('status');
            $table->string('lokasi')->nullable()->after('jadwal');
            $table->text('catatan')->nullable()->after('lokasi');
            $table->string('respon_pelamar', 50)->default('menunggu_konfirmasi')->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelamar_lowongans', function (Blueprint $table) {
            $table->dropColumn(['jadwal', 'lokasi', 'catatan', 'respon_pelamar']);
        });
    }
};
