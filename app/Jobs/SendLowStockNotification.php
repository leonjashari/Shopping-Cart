<?php

namespace App\Jobs;

use App\Mail\LowStockMail;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendLowStockNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Product $product)
    {}

    public function handle(): void
    {
        $recipient = config('mail.admin_address');

        if ($recipient) {
            Mail::to($recipient)->send(new LowStockMail($this->product));
        }
    }
}
