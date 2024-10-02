<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendTelegramValidationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected User $user, protected string $code)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->user->email,
            subject: __('Your validation code for').' '.config('app.name')
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mails.telegram-validation-code',
            with: [
                'user' => $this->user,
                'code' => $this->code
            ]
        );
    }
}
