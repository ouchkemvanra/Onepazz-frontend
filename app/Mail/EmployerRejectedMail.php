<?php

namespace App\Mail;

use App\Models\Employer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployerRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Employer $employer,
        public string $reason
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Update on your KhmerFit registration');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.employer-rejected');
    }
}
