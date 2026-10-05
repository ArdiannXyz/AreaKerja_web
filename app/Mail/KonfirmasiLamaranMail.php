<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KonfirmasiLamaranMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lowongan;
    public $pelamar;
    public $konfirmasi;
    public $pelamarlowongan;

    public function __construct($pelamar, $lowongan, $konfirmasi, $pelamarlowongan)
    {
        $this->pelamar        = $pelamar;
        $this->lowongan       = $lowongan;
        $this->konfirmasi     = $konfirmasi;
        $this->pelamarlowongan = $pelamarlowongan;
    }

    /**
     * Get the message envelope.
     * Subject menggunakan judul lowongan agar konsisten dengan konten email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Interview - ' . ($this->lowongan->judul ?? 'Lowongan'),
        );
    }

    /**
     * Get the message content definition.
     * Karena semua properti bersifat public, Laravel otomatis
     * meneruskan $pelamar, $lowongan, $konfirmasi, $pelamarlowongan ke view.
     * Variabel $data di-alias agar view lama tidak perlu diubah.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.konfirmasi-lamaran',
            with: [
                'data' => $this->pelamarlowongan,
            ],
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
