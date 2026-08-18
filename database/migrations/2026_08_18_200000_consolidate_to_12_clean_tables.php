<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Hard Migration: Menyatukan dan merampingkan database AreaKerja menjadi 12 Tabel Inti Bersih.
     */
    public function up(): void
    {
        // Matikan foreign key check sementara agar drop tabel lancar
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Drop tabel-tabel over-engineered / terpecah-pecah
        $tablesToDrop = [
            'superadmins',
            'admins',
            'finances',
            'alamatpelamars',
            'alamat_perusahaan',
            'social_media_pelamar',
            'skill',
            'pengalaman_organisasis',
            'divisis',
            'kotas',
            'provinsis',
            'kecamatans',
            'harga_koins',
            'harga_pembayarans',
            'pembeli_kandidats',
            'email_subscribers',
            'email_verifications',
            'password_verifications',
            'social_links',
            'events',
            'simpan_lowongans',
            'categories'
        ];

        foreach ($tablesToDrop as $table) {
            Schema::dropIfExists($table);
        }

        // 2. Sesuaikan Tabel Users (Menampung semua peran & data dasar)
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'nama_lengkap')) {
                    $table->string('nama_lengkap')->nullable()->after('username');
                }
                if (!Schema::hasColumn('users', 'telepon')) {
                    $table->string('telepon')->nullable()->after('email');
                }
                if (!Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable()->after('telepon');
                }
            });
        }

        // 3. Sesuaikan Tabel Perusahaans (Profil Perusahaan Terpadu + Alamat)
        if (Schema::hasTable('perusahaans')) {
            Schema::table('perusahaans', function (Blueprint $table) {
                if (!Schema::hasColumn('perusahaans', 'alamat')) {
                    $table->text('alamat')->nullable()->after('misi');
                }
                if (!Schema::hasColumn('perusahaans', 'kota')) {
                    $table->string('kota')->nullable()->after('alamat');
                }
                if (!Schema::hasColumn('perusahaans', 'provinsi')) {
                    $table->string('provinsi')->nullable()->after('kota');
                }
            });
        }

        // 4. Sesuaikan Tabel Pelamars (Profil Pelamar Terpadu + JSON Skill & JSON Sosmed)
        if (Schema::hasTable('pelamars')) {
            Schema::table('pelamars', function (Blueprint $table) {
                if (!Schema::hasColumn('pelamars', 'alamat')) {
                    $table->text('alamat')->nullable()->after('deskripsi_diri');
                }
                if (!Schema::hasColumn('pelamars', 'kota')) {
                    $table->string('kota')->nullable()->after('alamat');
                }
                if (!Schema::hasColumn('pelamars', 'provinsi')) {
                    $table->string('provinsi')->nullable()->after('kota');
                }
                if (!Schema::hasColumn('pelamars', 'skills')) {
                    $table->json('skills')->nullable()->after('provinsi');
                }
                if (!Schema::hasColumn('pelamars', 'social_links')) {
                    $table->json('social_links')->nullable()->after('skills');
                }
                if (!Schema::hasColumn('pelamars', 'resume_file')) {
                    $table->string('resume_file')->nullable()->after('social_links');
                }
            });
        }

        // 5. Sesuaikan Tabel Paket Lowongans (Memuat Harga Koin & Durasi)
        if (Schema::hasTable('paket_lowongans')) {
            Schema::table('paket_lowongans', function (Blueprint $table) {
                if (!Schema::hasColumn('paket_lowongans', 'harga_koin')) {
                    $table->integer('harga_koin')->default(100)->after('nama');
                }
            });
        }

        // 6. Sesuaikan Tabel Daftar Bank (Payment Channels)
        if (Schema::hasTable('daftar_bank')) {
            // Rename to standard daftar_banks if needed or maintain daftar_bank
        }

        // Aktifkan kembali foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
