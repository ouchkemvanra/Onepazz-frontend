<?php

namespace App\Mail;

use App\Models\Gym;
use App\Models\GymStaff;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public GymStaff $staffRecord,
        public ?string  $tempPassword = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'You have been added as staff — ' . $this->staffRecord->gym->name);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.staff-invite');
    }
}
