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
        Schema::create('iklans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('perusahaan_id');
            $table->string('judul_iklan');
            $table->string('gambar_banner');
            $table->string('url_tujuan');
            $table->string('paket_nama')->default('Standard');
            $table->integer('durasi_hari')->default(7);
            $table->integer('koin_terpotong')->default(300);
            $table->string('posisi')->default('home_hero');
            $table->enum('status', ['menunggu', 'aktif', 'ditolak', 'selesai'])->default('menunggu');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->unsignedBigInteger('total_views')->default(0);
            $table->unsignedBigInteger('total_clicks')->default(0);
            $table->timestamps();

            $table->foreign('perusahaan_id')->references('id')->on('perusahaans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iklans');
    }
};
