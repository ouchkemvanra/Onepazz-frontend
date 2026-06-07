<?php

namespace App\Mail;

use App\Models\Employer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployerActivatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Employer $employer) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your OnePazz account is now active!');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.employer-activated');
    }
}
