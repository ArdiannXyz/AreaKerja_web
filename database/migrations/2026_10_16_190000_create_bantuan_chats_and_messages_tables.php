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
        if (!Schema::hasTable('bantuan_chats')) {
            Schema::create('bantuan_chats', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('subjek')->default('Bantuan & Keluhan');
                $table->enum('status', ['menunggu', 'dibalas', 'selesai'])->default('menunggu');
                $table->timestamp('last_message_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('bantuan_messages')) {
            Schema::create('bantuan_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bantuan_chat_id')->constrained('bantuan_chats')->onDelete('cascade');
                $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
                $table->enum('sender_type', ['user', 'admin'])->default('user');
                $table->text('pesan');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bantuan_messages');
        Schema::dropIfExists('bantuan_chats');
    }
};

