<?php

namespace App\Mail;

use App\Models\RecipeDailySelection;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeAboardMail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(public User $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->user->email,
            subject: __('Bienvenue sur :appname !', ['appname' => config('app.name')]),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mails.welcome',
            with: [
                'url' => route('profile'),
            ]
        );
    }
}
