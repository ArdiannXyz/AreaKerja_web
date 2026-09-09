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
        Schema::table('email_subscribers', function (Blueprint $table) {
            $table->foreignId('pelamar_id')
                ->nullable()
                ->after('email')
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('perusahaan_id')
                ->nullable()
                ->after('pelamar_id')
                ->constrained()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('email_subscribers')) {
            Schema::table('email_subscribers', function (Blueprint $table) {
                $table->dropForeign(['pelamar_id']);
                $table->dropForeign(['perusahaan_id']);
                $table->dropColumn(['pelamar_id', 'perusahaan_id']);
            });
        }
    }
};
