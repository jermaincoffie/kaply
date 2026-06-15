<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialDag10Mail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public \App\Models\Kapper $kapper) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Nog 4 dagen resterend – zet ' . $this->kapper->salon_naam . ' live');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.trial-dag10');
    }

    public function attachments(): array
    {
        return [];
    }
}
