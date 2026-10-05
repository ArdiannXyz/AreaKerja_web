<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Membuat ulang tabel admins, finances, dan divisis yang sebelumnya
 * di-drop oleh migration consolidate (2026_08_18_200000).
 * Tabel-tabel ini masih dibutuhkan oleh SuperAdminController untuk
 * menyimpan data profil admin/finance dan data divisi kandidat.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Tabel admins — profil untuk user berole 'admin'
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
                $table->string('nama_lengkap')->nullable();
                $table->unsignedBigInteger('provinsi_id')->nullable();
                $table->unsignedBigInteger('kota_id')->nullable();
                $table->unsignedBigInteger('kecamatan_id')->nullable();
                $table->string('desa')->nullable();
                $table->string('kode_pos')->nullable();
                $table->text('detail_alamat')->nullable();
                $table->string('img_profile')->nullable();
                $table->string('akses_kota')->nullable();
                $table->timestamps();
            });
        }

        // Tabel finances — profil untuk user berole 'finance'
        if (!Schema::hasTable('finances')) {
            Schema::create('finances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
                $table->string('nama_lengkap')->nullable();
                $table->string('img_profile')->nullable();
                $table->unsignedBigInteger('provinsi_id')->nullable();
                $table->unsignedBigInteger('kota_id')->nullable();
                $table->unsignedBigInteger('kecamatan_id')->nullable();
                $table->string('desa')->nullable();
                $table->string('kode_pos')->nullable();
                $table->string('detail_alamat')->nullable();
                $table->timestamps();
            });
        }

        // Tabel divisis — master data divisi untuk form tambah kandidat
        if (!Schema::hasTable('divisis')) {
            Schema::create('divisis', function (Blueprint $table) {
                $table->id();
                $table->string('divisi')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('divisis');
        Schema::dropIfExists('finances');
        Schema::dropIfExists('admins');
    }
};
