<?php

namespace App\Mail;

use App\Models\GymApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GymInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public GymApplication $application,
        public ?string $personalMessage = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "You're invited to join KhmerFit");
    }

    public function content(): Content
    {
        return new Content(view: 'mail.gym-invitation');
    }
}
