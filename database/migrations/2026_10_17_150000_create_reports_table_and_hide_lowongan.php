<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lowongan_id')->constrained('lowongan_perusahaans')->cascadeOnDelete();
            $table->string('alasan');
            $table->text('deskripsi');
            $table->string('bukti_path')->nullable();
            $table->string('status')->default('pending');
            $table->string('action_taken')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['lowongan_id', 'status']);
            $table->index(['reporter_user_id', 'lowongan_id', 'status']);
        });

        Schema::table('lowongan_perusahaans', function (Blueprint $table) {
            if (! Schema::hasColumn('lowongan_perusahaans', 'status_sebelum_moderasi')) {
                $table->string('status_sebelum_moderasi')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lowongan_perusahaans', function (Blueprint $table) {
            if (Schema::hasColumn('lowongan_perusahaans', 'status_sebelum_moderasi')) {
                $table->dropColumn('status_sebelum_moderasi');
            }
        });

        Schema::dropIfExists('reports');
    }
};
