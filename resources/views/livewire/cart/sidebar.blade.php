<div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
    @if($cart && $cart->items->isNotEmpty())
        <ul class="divide-y divide-gray-200">
            @foreach($cart->items as $item)
                <li class="py-3 flex items-start gap-3">
                    <div class="flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-500">${{ number_format($item->unit_price, 2) }} each</p>
                            </div>
                            <div class="text-sm font-semibold text-gray-900">
                                ${{ number_format($item->unit_price * $item->quantity, 2) }}
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="mt-4 flex items-center justify-between">
            <div class="text-sm text-gray-600">Items: {{ $cart->items->sum('quantity') }}</div>
            <div class="text-lg font-semibold text-gray-900">
                Total: ${{ number_format($cart->items->sum(fn($i) => $i->quantity * $i->unit_price), 2) }}
            </div>
        </div>
    @else
        <p class="text-sm text-gray-500">Your cart is empty.</p>
    @endif
</div>
