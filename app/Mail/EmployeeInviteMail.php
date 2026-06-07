<?php

namespace App\Mail;

use App\Models\Employee;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployeeInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly Employee $employee,
        public readonly Employer $employer,
        public readonly string $tempPassword,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Welcome to OnePazz — {$this->employer->company_name}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.employee-invite');
    }
}
