<?php

namespace App\Mail;

use App\Models\EmployerInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployerInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public EmployerInvitation $invitation) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "You're invited to join KhmerFit Corporate Wellness");
    }

    public function content(): Content
    {
        return new Content(view: 'mail.employer-invitation');
    }
}
