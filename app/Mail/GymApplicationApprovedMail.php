<?php

namespace App\Mail;

use App\Models\GymApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GymApplicationApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public GymApplication $application,
        public ?string $loginEmail = null,
        public ?string $tempPassword = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome to OnePazz! Your application is approved');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.gym-application-approved');
    }
}
