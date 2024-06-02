<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    private string $token, $expiresAt;

    /**
     * Create a new message instance.
     */
    public function __construct(string $token, string $expirationAt)
    {
        $this->token = $token;
        $this->expiresAt = $expirationAt;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Invoice-API, complete your registration',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.invitation',
            with: [
                'url' => env('APP_FRONTEND_URL').'?token='. $this->token,
                'expiresAt' => $this->expiresAt
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
