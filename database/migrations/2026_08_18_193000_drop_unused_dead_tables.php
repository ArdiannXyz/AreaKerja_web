<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membersihkan tabel zombie / duplikat / dead code yang tidak digunakan.
     */
    public function up(): void
    {
        // 1. Drop tabel duplikat alamat_pelamars (yang aktif digunakan adalah alamatpelamars)
        Schema::dropIfExists('alamat_pelamars');

        // 2. Drop tabel divisi_pelamars (digantikan oleh relasi langsung pelamars.divisi -> divisis.id)
        Schema::dropIfExists('divisi_pelamars');

        // 3. Drop tabel kegiatan_events (dead code, tidak ada controller/view yang memprosesnya)
        Schema::dropIfExists('kegiatan_events');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback placeholder
    }
};
