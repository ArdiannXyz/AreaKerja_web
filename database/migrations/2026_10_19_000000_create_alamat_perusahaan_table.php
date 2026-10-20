<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AlamatPerusahaan;

return new class extends Migration
{
    /**
     * Create alamat_perusahaan table for multi-address companies.
     * One address can be marked as utama (main).
     */
    public function up(): void
    {
        Schema::create('alamat_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->cascadeOnDelete();
            $table->string('desa')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();
            $table->text('detail')->nullable();
            $table->string('label')->nullable();
            $table->boolean('utama')->default(0);
            $table->timestamps();
        });

        // Migrate existing company addresses to alamat_perusahaan (as main address)
        if (\Schema::hasTable('perusahaans') && \Schema::hasColumn('perusahaans', 'alamat')) {
            \App\Models\Perusahaan::whereNotNull('alamat')->chunk(100, function ($perusahaans) {
                foreach ($perusahaans as $p) {
                    if (! empty($p->alamat)) {
                        AlamatPerusahaan::create([
                            'perusahaan_id' => $p->id,
                            'label'     => 'Alamat Utama',
                            'desa'      => null,
                            'kota'      => $p->kota,
                            'provinsi'  => $p->provinsi,
                            'kode_pos'  => null,
                            'detail'    => $p->alamat,
                            'utama'     => true,
                        ]);
                    }
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('alamat_perusahaan');
    }
};
