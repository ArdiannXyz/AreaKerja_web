<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Membuat ulang tabel superadmins yang sebelumnya di-drop oleh
 * migration consolidate (2026_08_18_200000).
 * Tabel ini dibutuhkan untuk menyimpan data profil super admin.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('superadmins')) {
            Schema::create('superadmins', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
                $table->string('nama_lengkap')->nullable();
                $table->string('img_profile')->nullable();
                $table->string('provinsi')->nullable();
                $table->string('kota')->nullable();
                $table->string('kecamatan')->nullable();
                $table->string('desa')->nullable();
                $table->string('kode_pos')->nullable();
                $table->string('detail_alamat')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('superadmins');
    }
};
