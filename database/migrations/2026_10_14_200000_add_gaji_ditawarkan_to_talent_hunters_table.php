<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('talent_hunters', function (Blueprint $table) {
            if (!Schema::hasColumn('talent_hunters', 'gaji_ditawarkan')) {
                $table->unsignedBigInteger('gaji_ditawarkan')->nullable()->after('gender');
            }
        });
    }

    public function down(): void
    {
        Schema::table('talent_hunters', function (Blueprint $table) {
            if (Schema::hasColumn('talent_hunters', 'gaji_ditawarkan')) {
                $table->dropColumn('gaji_ditawarkan');
            }
        });
    }
};

