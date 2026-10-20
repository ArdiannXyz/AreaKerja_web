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
        Schema::table('iklans', function (Blueprint $table) {
            $table->unsignedBigInteger('perusahaan_id')->nullable()->change();
            if (!Schema::hasColumn('iklans', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('perusahaan_id');
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iklans', function (Blueprint $table) {
            if (Schema::hasColumn('iklans', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            $table->unsignedBigInteger('perusahaan_id')->nullable(false)->change();
        });
    }
};
