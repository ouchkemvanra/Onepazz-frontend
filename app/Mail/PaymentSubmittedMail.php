<?php

namespace App\Mail;

use App\Models\Employer;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Invoice $invoice,
        public readonly Employer $employer,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[KhmerFit] Payment Submitted — {$this->employer->company_name}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.payment-submitted');
    }
}
