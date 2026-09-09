<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('alasan_freeze_akun')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'alasan_freeze_akun')) {
            \Illuminate\Support\Facades\DB::table('users')->whereNull('alasan_freeze_akun')->update(['alasan_freeze_akun' => '']);
            Schema::table('users', function (Blueprint $table) {
                $table->string('alasan_freeze_akun')->default('')->change();
            });
        }
    }
};
