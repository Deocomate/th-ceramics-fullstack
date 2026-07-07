<?php

namespace App\Mail;

use App\Models\ConsultationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConsultationConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ConsultationRequest $record) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thanh Hải đã nhận yêu cầu tư vấn của bạn',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'components.emails.consultation.confirmation',
            with: ['record' => $this->record],
        );
    }
}
