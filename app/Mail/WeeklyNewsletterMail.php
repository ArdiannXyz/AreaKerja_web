<?php

namespace App\Mail;

use App\Models\EmailSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class WeeklyNewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lowongans;
    public $subscriber;
    public $unsubscribeUrl;

    /**
     * Create a new message instance.
     *
     * @param \Illuminate\Support\Collection $lowongans
     * @param \App\Models\EmailSubscriber $subscriber
     */
    public function __construct($lowongans, EmailSubscriber $subscriber)
    {
        $this->lowongans = $lowongans;
        $this->subscriber = $subscriber;
        $this->unsubscribeUrl = URL::signedRoute('newsletter.unsubscribe', [
            'email' => $subscriber->email,
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔥 Rekomendasi Lowongan Kerja Terbaru Minggu Ini - AreaKerja',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter-weekly',
            with: [
                'lowongans'      => $this->lowongans,
                'subscriber'     => $this->subscriber,
                'unsubscribeUrl' => $this->unsubscribeUrl,
            ],
        );
    }

    /**
     * Set email headers compliant with RFC 8058 (One-Click Unsubscribe for Gmail / Yahoo).
     */
    public function headers(): Headers
    {
        return new Headers(
            text: [
                'List-Unsubscribe'      => '<' . $this->unsubscribeUrl . '>',
                'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
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

