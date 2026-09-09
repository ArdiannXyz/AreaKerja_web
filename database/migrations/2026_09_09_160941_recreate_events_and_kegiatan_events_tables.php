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
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->string('status')->default('buka');
                $table->string('title');
                $table->integer('kuota')->nullable();
                $table->string('image')->nullable();
                $table->mediumText('content')->nullable();
                $table->date('tgl_mulai');
                $table->string('jam_mulai', 10);
                $table->date('tgl_akhir');
                $table->string('jam_akhir', 10);
                $table->text('lokasi')->nullable();
                $table->string('link_form')->nullable();
                $table->date('penutupan_pendaftaran')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kegiatan_events')) {
            Schema::create('kegiatan_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
                $table->string('waktu')->nullable();
                $table->string('kegiatan')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_events');
        Schema::dropIfExists('events');
    }
};
