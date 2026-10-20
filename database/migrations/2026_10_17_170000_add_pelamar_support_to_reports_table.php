<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Ubah lowongan_id agar nullable jika sebelumnya not null
            $table->unsignedBigInteger('lowongan_id')->nullable()->change();

            // Tambahkan tipe, pelamar_id, dan reported_user_id jika belum ada
            if (! Schema::hasColumn('reports', 'tipe')) {
                $table->string('tipe', 20)->default('lowongan')->after('id');
            }
            if (! Schema::hasColumn('reports', 'pelamar_id')) {
                $table->foreignId('pelamar_id')->nullable()->after('lowongan_id')->constrained('pelamars')->cascadeOnDelete();
            }
            if (! Schema::hasColumn('reports', 'reported_user_id')) {
                $table->foreignId('reported_user_id')->nullable()->after('pelamar_id')->constrained('users')->cascadeOnDelete();
            }
        });

        $this->createIndexIfMissing('reports', ['tipe', 'status'], 'reports_tipe_status_index');
        $this->createIndexIfMissing('reports', ['pelamar_id', 'status'], 'reports_pelamar_id_status_index');
        $this->createIndexIfMissing('reports', ['reporter_user_id', 'pelamar_id', 'status'], 'reports_reporter_user_id_pelamar_id_status_index');
    }

    public function down(): void
    {
        // 1. Drop foreign keys first to release any foreign key constraint locks on MySQL indexes
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'reported_user_id')) {
                $table->dropConstrainedForeignId('reported_user_id');
            }
            if (Schema::hasColumn('reports', 'pelamar_id')) {
                $table->dropConstrainedForeignId('pelamar_id');
            }
        });

        // 2. Drop indexes safely if they exist
        $this->dropIndexIfExists('reports', 'reports_tipe_status_index');
        $this->dropIndexIfExists('reports', 'reports_pelamar_id_status_index');
        $this->dropIndexIfExists('reports', 'reports_reporter_user_id_pelamar_id_status_index');

        // 3. Drop columns and restore lowongan_id
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'tipe')) {
                $table->dropColumn('tipe');
            }

            $table->unsignedBigInteger('lowongan_id')->nullable(false)->change();
        });
    }

    private function createIndexIfMissing(string $table, array $columns, string $indexName): void
    {
        $exists = collect(DB::select("SHOW INDEX FROM `{$table}`"))->contains('Key_name', $indexName);
        if (! $exists) {
            Schema::table($table, function (Blueprint $t) use ($columns, $indexName) {
                $t->index($columns, $indexName);
            });
        }
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        $exists = collect(DB::select("SHOW INDEX FROM `{$table}`"))->contains('Key_name', $indexName);
        if ($exists) {
            Schema::table($table, function (Blueprint $t) use ($indexName) {
                $t->dropIndex($indexName);
            });
        }
    }
};

