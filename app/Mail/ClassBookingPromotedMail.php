<?php

namespace App\Mail;

use App\Models\ClassBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClassBookingPromotedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ClassBooking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'You\'re in! Your waitlist spot is confirmed — KhmerFit');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.class-booking-promoted');
    }
}
