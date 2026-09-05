<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Membuat ulang tabel pembeli_kandidats yang sebelumnya
 * di-drop oleh migration consolidate (2026_08_18_200000).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pembeli_kandidats')) {
            Schema::create('pembeli_kandidats', function (Blueprint $table) {
                $table->id();
                $table->string('no_referensi')->unique();
                $table->foreignId('pelamar_id')->constrained('pelamars')->onDelete('cascade')->onUpdate('cascade');
                $table->foreignId('lowongan_perusahaan_id')->constrained('lowongan_perusahaans')->onDelete('cascade')->onUpdate('cascade');
                $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
                $table->text('alasan_penolakan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pembeli_kandidats');
    }
};
