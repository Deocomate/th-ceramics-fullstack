<?php

namespace App\Mail;

use App\Models\ConsultationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConsultationRequestedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ConsultationRequest $record) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Yêu cầu tư vấn mới từ website Thanh Hải',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'components.emails.consultation.requested',
            with: ['record' => $this->record],
        );
    }
}
