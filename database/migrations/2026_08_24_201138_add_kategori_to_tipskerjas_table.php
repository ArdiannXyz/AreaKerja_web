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
        if (!Schema::hasColumn('tipskerjas', 'kategori')) {
            Schema::table('tipskerjas', function (Blueprint $table) {
                $table->string('kategori')->default('Tips Kerja')->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('tipskerjas', 'kategori')) {
            Schema::table('tipskerjas', function (Blueprint $table) {
                $table->dropColumn('kategori');
            });
        }
    }
};
