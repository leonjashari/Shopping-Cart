@component('mail::message')
# Daily Sales Report ({{ $date->toFormattedDateString() }})

@if($lines->isEmpty())
No sales recorded today.
@else
| Product | Quantity | Revenue |
| --- | --- | --- |
@foreach($lines as $line)
| {{ $line['name'] }} | {{ $line['quantity'] }} | ${{ number_format($line['revenue'], 2) }} |
@endforeach
| **Total** |  | **${{ number_format($total, 2) }}** |
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
