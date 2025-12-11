@php
    $placeholder = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80';
@endphp

<div class="relative">
    <div class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white">
        <div class="absolute inset-0 pointer-events-none opacity-40" aria-hidden="true">
            <div class="absolute -left-20 top-10 h-64 w-64 rounded-full bg-indigo-200 blur-3xl"></div>
            <div class="absolute -right-10 top-32 h-72 w-72 rounded-full bg-emerald-200 blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-6xl space-y-10 px-4 py-12 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500">Shopping cart</p>
                    <h1 class="mt-1 text-3xl font-bold text-gray-900">Simple E-commerce</h1>
                    <p class="text-sm text-gray-600">Browse curated products, add them to your cart, and keep everything tied to your account.</p>
                </div>
                <div class="rounded-xl border border-white/60 bg-white/80 px-4 py-3 shadow-sm backdrop-blur">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Signed in</p>
                    <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <div class="min-h-[0px] space-y-2">
                @if ($errors->has('cart'))
                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first('cart') }}
                    </div>
                @endif
            </div>

            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-semibold text-gray-900">Products</h2>
                            <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">{{ $products->count() }} available</span>
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        @forelse($products as $product)
                            <div class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg" wire:key="product-{{ $product->id }}">
                                <div class="aspect-[4/3] overflow-hidden bg-gray-100">
                                    <img src="{{ $product->image_url ?? $placeholder }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                </div>
                                <div class="space-y-3 p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                                            <p class="text-sm text-gray-600 line-clamp-2">{{ $product->description }}</p>
                                        </div>
                                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">Stock: {{ $product->stock_quantity }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="text-2xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</div>
                                        <div class="flex items-center gap-2">
                                            <label for="qty-{{ $product->id }}" class="text-xs text-gray-500">Qty</label>
                                            <input
                                                id="qty-{{ $product->id }}"
                                                type="number"
                                                min="1"
                                                class="w-20 rounded-lg border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                wire:model.live="quantities.{{ $product->id }}"
                                                wire:loading.attr="disabled"
                                                wire:target="addToCart"
                                            />
                                        </div>
                                    </div>
                                    <button
                                        wire:click="addToCart({{ $product->id }})"
                                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:cursor-not-allowed disabled:opacity-60"
                                        wire:loading.attr="disabled"
                                        wire:target="addToCart"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l1.6 9m0 0h10.8l1.2-6H6.2m0 0L5.6 3m3.4 15a1.2 1.2 0 11-2.4 0 1.2 1.2 0 012.4 0zm10 0a1.2 1.2 0 11-2.4 0 1.2 1.2 0 012.4 0z" />
                                        </svg>
                                        Add to cart
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-lg border border-dashed border-gray-200 bg-white p-6 text-center text-gray-500">
                                No products yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Cart</h2>
                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">Items: {{ $cart->items->sum('quantity') }}</span>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                        @if($cart->items->isEmpty())
                            <p class="text-sm text-gray-500">Your cart is empty.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($cart->items as $item)
                                    <li class="py-3 flex items-start gap-3" wire:key="cart-item-{{ $item->id }}">
                                        <div class="size-12 overflow-hidden rounded-lg bg-gray-100">
                                            <img src="{{ $item->product->image_url ?? $placeholder }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $item->product->name }}</p>
                                                    <p class="text-xs text-gray-500">${{ number_format($item->unit_price, 2) }} each</p>
                                                </div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    ${{ number_format($item->unit_price * $item->quantity, 2) }}
                                                </div>
                                            </div>
                                            <div class="mt-2 flex items-center gap-2">
                                                <input
                                                    type="number"
                                                    min="0"
                                                    class="w-20 rounded-lg border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    value="{{ $item->quantity }}"
                                                    wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                                                    wire:loading.attr="disabled"
                                                    wire:target="updateQuantity,removeItem,checkout"
                                                />
                                                <button
                                                    class="text-sm text-red-600 hover:text-red-500"
                                                    wire:click="removeItem({{ $item->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="removeItem"
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-4 flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2">
                                <div class="text-sm text-gray-600">Items: {{ $cart->items->sum('quantity') }}</div>
                                <div class="text-lg font-semibold text-gray-900">
                                    Total: ${{ number_format($cart->items->sum(fn($i) => $i->quantity * $i->unit_price), 2) }}
                                </div>
                            </div>

                            @error('checkout')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <button
                                wire:click="checkout"
                                class="mt-4 w-full inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 disabled:cursor-not-allowed disabled:opacity-60"
                                wire:loading.attr="disabled"
                                wire:target="checkout"
                            >
                                Complete checkout
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        x-data="{ show: @entangle('banner').defer }"
        x-show="show"
        x-transition
        x-init="if (show) setTimeout(() => show = false, 5000)"
        @banner.window="show = true; setTimeout(() => show = false, 5000)"
        class="fixed bottom-6 right-6 z-40 w-full max-w-sm rounded-xl border border-emerald-200 bg-white/90 px-4 py-3 text-sm text-emerald-900 shadow-lg backdrop-blur"
        style="display: none;"
    >
        <div class="flex items-start gap-3">
            <div class="mt-0.5 rounded-full bg-emerald-100 p-1.5 text-emerald-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="font-semibold">Success</p>
                <p class="text-gray-700" x-text="show ? @js($banner ?? '') : ''"></p>
            </div>
            <button class="text-gray-400 hover:text-gray-600" @click="show = false">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>
