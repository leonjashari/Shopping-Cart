<button
    class="relative inline-flex items-center gap-2 rounded-md border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50"
    type="button"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l1.6 9m0 0h10.8l1.2-6H6.2m0 0L5.6 3m3.4 15a1.2 1.2 0 11-2.4 0 1.2 1.2 0 012.4 0zm10 0a1.2 1.2 0 11-2.4 0 1.2 1.2 0 012.4 0z" />
    </svg>
    <span>Cart</span>
    @if($count > 0)
        <span class="inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-indigo-600 px-1 text-xs font-semibold text-white">
            {{ $count }}
        </span>
    @endif
</button>
