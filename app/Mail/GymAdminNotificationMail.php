<?php

namespace App\Mail;

use App\Models\GymApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GymAdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public GymApplication $application,
        public string $event = 'new_application'
    ) {}

    public function envelope(): Envelope
    {
        $subject = match($this->event) {
            'accepted' => "[KhmerFit] {$this->application->studio_name} accepted your invitation",
            default    => "[KhmerFit] New gym application from {$this->application->studio_name}",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.gym-admin-notification');
    }
}
