<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailySalesReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly \Illuminate\Support\Collection $lines,
        public readonly float $total,
        public readonly \Carbon\Carbon $date,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily sales report - ' . $this->date->toFormattedDateString(),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.daily-sales-report',
            with: [
                'lines' => $this->lines,
                'total' => $this->total,
                'date' => $this->date,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
