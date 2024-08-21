<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Zus1\LaravelAuth\Models\Token;


class InvitationMail extends Mailable
{
    /**
     * Create a new message instance.
     */
    public function __construct(
        private Token $token,
    ){
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('laravel-auth.subject.invitation'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            text: config('laravel-auth.email.templates.invitation.txt'),
            markdown: config('laravel-auth.email.templates.invitation.markdown'),
            with: [
                'url' => sprintf(
                    '%s?token=%s', config('laravel-auth.email.redirect_url.invitation'),
                    $this->token->token
                ),
            ],
        );
    }
}
