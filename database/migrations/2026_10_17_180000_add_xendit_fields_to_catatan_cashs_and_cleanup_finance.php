<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah field integrasi Xendit ke tabel catatan_cashs
        Schema::table('catatan_cashs', function (Blueprint $table) {
            if (!Schema::hasColumn('catatan_cashs', 'checkout_url')) {
                $table->text('checkout_url')->nullable()->after('bukti');
            }
            if (!Schema::hasColumn('catatan_cashs', 'xendit_id')) {
                $table->string('xendit_id')->nullable()->after('checkout_url');
            }
            if (!Schema::hasColumn('catatan_cashs', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('expired_at');
            }
        });

        // 2. Bersihkan akun user ber-role 'finance' (jika ada, hapus record finance terkait dan akun/update)
        try {
            if (Schema::hasTable('finances')) {
                DB::table('finances')->truncate();
                Schema::dropIfExists('finances');
            }
            DB::table('users')->where('role', 'finance')->delete();
        } catch (\Throwable $e) {
            // Abaikan jika tabel atau data sudah tidak ada
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catatan_cashs', function (Blueprint $table) {
            if (Schema::hasColumn('catatan_cashs', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
            if (Schema::hasColumn('catatan_cashs', 'xendit_id')) {
                $table->dropColumn('xendit_id');
            }
            if (Schema::hasColumn('catatan_cashs', 'checkout_url')) {
                $table->dropColumn('checkout_url');
            }
        });
    }
};

