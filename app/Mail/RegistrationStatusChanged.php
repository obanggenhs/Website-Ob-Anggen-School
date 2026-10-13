<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Registration $registration, public ?string $note = null)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Status pendaftaran '.$this->registration->number.': '.$this->registration->status_label);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.registration-status');
    }
}
