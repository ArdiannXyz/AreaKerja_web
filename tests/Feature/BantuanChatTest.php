<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\BantuanChat;
use App\Models\BantuanMessage;
use App\Models\Pelamar;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BantuanChatTest extends TestCase
{
    use DatabaseTransactions;

    public function test_faq_page_displays_contact_admin_banner(): void
    {
        $response = $this->get(route('faq'));

        $response->assertStatus(200);
        $response->assertSee('Tidak menemukan jawaban?');
        $response->assertSee(route('bantuan.chat'));
    }

    public function test_guest_is_redirected_to_login_when_accessing_bantuan_chat(): void
    {
        $response = $this->get(route('bantuan.chat'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_chat_and_send_message(): void
    {
        $timestamp = time();
        $user = User::create([
            'username'     => 'user_test_' . $timestamp,
            'nama_lengkap' => 'User Test Bantuan',
            'email'        => 'user_test_' . $timestamp . '@example.com',
            'password'     => bcrypt('secret123'),
            'role'         => 'pelamar',
            'verified'     => true,
            'status'       => 0,
        ]);

        Pelamar::create([
            'user_id'      => $user->id,
            'nama_lengkap' => 'User Test Bantuan',
            'email'        => $user->email,
            'verified'     => 1,
            'status'       => '0',
        ]);

        $response = $this->actingAs($user)->get(route('bantuan.chat'));
        $response->assertStatus(200);
        $response->assertSee('Layanan Bantuan');

        // User sends a message
        $sendResponse = $this->actingAs($user)->post(route('bantuan.chat.send'), [
            'pesan' => 'Halo admin, saya mengalami kendala pada unggah berkas CV.',
        ]);

        $sendResponse->assertRedirect(route('bantuan.chat'));

        // Verify saved in database
        $this->assertDatabaseHas('bantuan_chats', [
            'user_id' => $user->id,
            'status' => 'menunggu',
        ]);

        $chat = BantuanChat::where('user_id', $user->id)->first();
        $this->assertNotNull($chat);

        $this->assertDatabaseHas('bantuan_messages', [
            'bantuan_chat_id' => $chat->id,
            'sender_id' => $user->id,
            'sender_type' => 'user',
            'pesan' => 'Halo admin, saya mengalami kendala pada unggah berkas CV.',
        ]);

        // Access again - verify chat history is persistent
        $historyResponse = $this->actingAs($user)->get(route('bantuan.chat'));
        $historyResponse->assertStatus(200);
        $historyResponse->assertSee('Halo admin, saya mengalami kendala pada unggah berkas CV.');
    }

    public function test_admin_can_view_inquiries_and_reply(): void
    {
        $timestamp = time() . '_2';
        $user = User::create([
            'username'     => 'pelamar_' . $timestamp,
            'nama_lengkap' => 'Pelamar Inquiry',
            'email'        => 'pelamar_' . $timestamp . '@example.com',
            'password'     => bcrypt('secret123'),
            'role'         => 'pelamar',
            'verified'     => true,
            'status'       => 0,
        ]);

        Pelamar::create([
            'user_id'      => $user->id,
            'nama_lengkap' => 'Pelamar Inquiry',
            'email'        => $user->email,
            'verified'     => 1,
            'status'       => '0',
        ]);

        $admin = User::create([
            'username'     => 'admin_' . $timestamp,
            'nama_lengkap' => 'Admin Support',
            'email'        => 'admin_' . $timestamp . '@example.com',
            'password'     => bcrypt('secret123'),
            'role'         => 'admin',
            'verified'     => true,
            'status'       => 0,
        ]);

        Admin::create([
            'user_id'      => $admin->id,
            'nama_lengkap' => 'Admin Support',
            'email'        => $admin->email,
            'verified'     => 1,
            'status'       => 0,
        ]);

        // Create chat and message from user
        $chat = BantuanChat::create([
            'user_id' => $user->id,
            'subjek' => 'Bantuan & Keluhan Pengguna',
            'status' => 'menunggu',
            'last_message_at' => now(),
        ]);

        BantuanMessage::create([
            'bantuan_chat_id' => $chat->id,
            'sender_id' => $user->id,
            'sender_type' => 'user',
            'pesan' => 'Mohon bantuan untuk cek status akun saya.',
        ]);

        // Admin checks index
        $indexResponse = $this->actingAs($admin)->get(route('admin.bantuan.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Mohon bantuan untuk cek status akun saya.');

        // Admin views thread
        $showResponse = $this->actingAs($admin)->get(route('admin.bantuan.show', $chat->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Mohon bantuan untuk cek status akun saya.');

        // Admin replies
        $replyResponse = $this->actingAs($admin)->post(route('admin.bantuan.reply', $chat->id), [
            'pesan' => 'Halo, akun Anda sudah kami periksa dan aktif kembali.',
        ]);

        $replyResponse->assertRedirect(route('admin.bantuan.show', $chat->id));

        $this->assertDatabaseHas('bantuan_messages', [
            'bantuan_chat_id' => $chat->id,
            'sender_id' => $admin->id,
            'sender_type' => 'admin',
            'pesan' => 'Halo, akun Anda sudah kami periksa dan aktif kembali.',
        ]);

        $chat->refresh();
        $this->assertEquals('dibalas', $chat->status);

        // User views chat again and sees admin reply
        $userChatResponse = $this->actingAs($user)->get(route('bantuan.chat'));
        $userChatResponse->assertStatus(200);
        $userChatResponse->assertSee('Halo, akun Anda sudah kami periksa dan aktif kembali.');
    }

    public function test_superadmin_can_view_inquiries_and_reply(): void
    {
        $timestamp = time() . '_3';
        $user = User::create([
            'username'     => 'pelamar_' . $timestamp,
            'nama_lengkap' => 'Pelamar Super',
            'email'        => 'pelamar_' . $timestamp . '@example.com',
            'password'     => bcrypt('secret123'),
            'role'         => 'pelamar',
            'verified'     => true,
            'status'       => 0,
        ]);

        $superadmin = User::create([
            'username'     => 'superadmin_' . $timestamp,
            'nama_lengkap' => 'Super Admin Support',
            'email'        => 'superadmin_' . $timestamp . '@example.com',
            'password'     => bcrypt('secret123'),
            'role'         => 'super_admin',
            'verified'     => true,
            'status'       => 0,
        ]);

        $chat = BantuanChat::create([
            'user_id' => $user->id,
            'subjek' => 'Bantuan & Keluhan Pengguna',
            'status' => 'menunggu',
            'last_message_at' => now(),
        ]);

        BantuanMessage::create([
            'bantuan_chat_id' => $chat->id,
            'sender_id' => $user->id,
            'sender_type' => 'user',
            'pesan' => 'Ada kendala transaksi paket.',
        ]);

        $indexResponse = $this->actingAs($superadmin)->get(route('superadmin.bantuan.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Ada kendala transaksi paket.');

        $replyResponse = $this->actingAs($superadmin)->post(route('superadmin.bantuan.reply', $chat->id), [
            'pesan' => 'Sedang kami cek ke pihak finance ya.',
        ]);

        $replyResponse->assertRedirect(route('superadmin.bantuan.show', $chat->id));

        $this->assertDatabaseHas('bantuan_messages', [
            'bantuan_chat_id' => $chat->id,
            'sender_id' => $superadmin->id,
            'sender_type' => 'admin',
            'pesan' => 'Sedang kami cek ke pihak finance ya.',
        ]);
    }
}


