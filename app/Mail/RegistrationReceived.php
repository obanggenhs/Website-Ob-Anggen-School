<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Registration $registration, public bool $forCommittee = false)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->forCommittee
                ? 'Pendaftar baru: '.$this->registration->full_name.' ('.$this->registration->number.')'
                : 'Pendaftaran diterima - '.$this->registration->number,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.registration-received');
    }
}
