<?php

namespace App\Mail;

use App\Models\Employer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployerWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Employer $employer,
        public string $tempPassword
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome to KhmerFit!');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.employer-welcome');
    }
}
