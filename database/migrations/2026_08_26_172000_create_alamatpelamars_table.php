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
        if (!Schema::hasTable('alamatpelamars')) {
            Schema::create('alamatpelamars', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pelamar_id')->nullable();
                $table->string('label')->nullable();
                $table->string('provinsi')->nullable();
                $table->string('kota')->nullable();
                $table->string('kecamatan')->nullable();
                $table->string('desa')->nullable();
                $table->string('kode_pos')->nullable();
                $table->text('detail')->nullable();
                $table->boolean('is_primary')->default(false);
                $table->timestamps();

                $table->index('pelamar_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamatpelamars');
    }
};
