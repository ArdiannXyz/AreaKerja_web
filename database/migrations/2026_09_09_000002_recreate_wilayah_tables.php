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
        if (!Schema::hasTable('provinsis')) {
            Schema::create('provinsis', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kotas')) {
            Schema::create('kotas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('provinsi_id')->constrained('provinsis')->cascadeOnDelete();
                $table->string('nama');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kecamatans')) {
            Schema::create('kecamatans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kota_id')->constrained('kotas')->cascadeOnDelete();
                $table->string('nama');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatans');
        Schema::dropIfExists('kotas');
        Schema::dropIfExists('provinsis');
    }
};
