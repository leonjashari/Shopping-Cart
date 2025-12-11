@component('mail::message')
# Low stock alert

**Product:** {{ $product->name }}

**Remaining stock:** {{ $product->stock_quantity }}

**Threshold:** {{ $product->low_stock_threshold }}

Please consider restocking soon.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
