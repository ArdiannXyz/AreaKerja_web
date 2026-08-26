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
        if (!Schema::hasColumn('lowongan_perusahaans', 'status')) {
            Schema::table('lowongan_perusahaans', function (Blueprint $table) {
                $table->string('status')->default('buka')->after('batas_lamaran');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('lowongan_perusahaans', 'status')) {
            Schema::table('lowongan_perusahaans', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
