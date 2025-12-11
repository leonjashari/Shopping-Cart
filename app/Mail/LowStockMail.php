<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowStockMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly \App\Models\Product $product)
    {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Low stock alert: ' . $this->product->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.low-stock',
            with: [
                'product' => $this->product,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
