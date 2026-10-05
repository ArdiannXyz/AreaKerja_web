<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('superadmins', function (Blueprint $table) {
            $table->string('provinsi')->nullable()->change();
            $table->string('kota')->nullable()->change();
            $table->string('kecamatan')->nullable()->change();
            $table->string('desa')->nullable()->change();
            $table->string('kode_pos')->nullable()->change();
            $table->string('detail_alamat')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('superadmins')) {
            Schema::table('superadmins', function (Blueprint $table) {
                $table->string('provinsi')->change();
                $table->string('kota')->change();
                $table->string('kecamatan')->change();
                $table->string('desa')->change();
                $table->string('kode_pos')->change();
                $table->string('detail_alamat')->change();
            });
        }
    }
};
