<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add gaji_hidden field for VIP lowongan salary hiding.
     */
    public function up(): void
    {
        Schema::table('lowongan_perusahaans', function (Blueprint $table) {
            $table->boolean('gaji_hidden')->default(false)->after('label_gaji');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lowongan_perusahaans', function (Blueprint $table) {
            $table->dropColumn(['gaji_hidden']);
        });
    }
};
