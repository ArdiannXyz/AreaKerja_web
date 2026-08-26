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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'provinsi_id')) {
                $table->string('provinsi_id')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'kota_id')) {
                $table->string('kota_id')->nullable()->after('provinsi_id');
            }
            if (!Schema::hasColumn('users', 'kecamatan_id')) {
                $table->string('kecamatan_id')->nullable()->after('kota_id');
            }
            if (!Schema::hasColumn('users', 'desa')) {
                $table->string('desa')->nullable()->after('kecamatan_id');
            }
            if (!Schema::hasColumn('users', 'kode_pos')) {
                $table->string('kode_pos')->nullable()->after('desa');
            }
            if (!Schema::hasColumn('users', 'detail_alamat')) {
                $table->text('detail_alamat')->nullable()->after('kode_pos');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['provinsi_id', 'kota_id', 'kecamatan_id', 'desa', 'kode_pos', 'detail_alamat']);
        });
    }
};
