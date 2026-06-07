<?php

namespace App\Mail;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GymWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Gym $gym,
        public string $tempPassword
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome to OnePazz Partner Portal');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.gym-welcome');
    }
}
