<?php

namespace App\Jobs;

use App\Mail\DailySalesReportMail;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReport implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Carbon $date)
    {}

    public function handle(): void
    {
        $start = $this->date->copy()->startOfDay();
        $end = $this->date->copy()->endOfDay();

        $lines = OrderItem::with('product')
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->groupBy('product_id')
            ->map(fn ($items) => [
                'name' => $items->first()?->product?->name ?? 'Product',
                'quantity' => $items->sum('quantity'),
                'revenue' => $items->sum(fn (OrderItem $item) => $item->quantity * $item->unit_price),
            ])
            ->values();

        $total = $lines->sum('revenue');
        $recipient = config('mail.admin_address');

        if ($recipient) {
            Mail::to($recipient)->send(new DailySalesReportMail($lines, $total, $this->date));
        }
    }
}
