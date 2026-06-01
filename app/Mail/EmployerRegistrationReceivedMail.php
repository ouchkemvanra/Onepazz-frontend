<?php

namespace App\Mail;

use App\Models\Employer;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployerRegistrationReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Employer $employer,
        public Invoice $invoice,
        public array $bankDetails
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'KhmerFit Registration Received — Action Required');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.employer-registration-received');
    }
}
