<?php

namespace App\Mail;

use App\Models\GymApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GymApplicationRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public GymApplication $application,
        public string $reason
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Update on your OnePazz application');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.gym-application-rejected');
    }
}
