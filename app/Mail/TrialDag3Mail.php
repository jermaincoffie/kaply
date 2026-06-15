<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialDag3Mail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public \App\Models\Kapper $kapper) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Hoe gaat het bij ' . $this->kapper->salon_naam . '?');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.trial-dag3');
    }

    public function attachments(): array
    {
        return [];
    }
}
