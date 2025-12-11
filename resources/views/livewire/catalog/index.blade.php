<div class="py-10">
    <div class="mx-auto max-w-6xl space-y-10 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Simple E-commerce</h1>
                <p class="text-sm text-gray-500">Browse products and manage your cart. Data stays on your account.</p>
            </div>
            <div class="text-sm text-gray-600">
                Logged in as <span class="font-medium text-gray-900">{{ auth()->user()->email }}</span>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800 ring-1 ring-green-200">
                {{ session('success') }}
            </div>
        @endif

        @error('cart')
            <div class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-200">
                {{ $message }}
            </div>
        @enderror

        <div class="grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Products</h2>
                    <span class="text-sm text-gray-500">{{ $products->count() }} available</span>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    @forelse($products as $product)
                        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="size-16 rounded-lg bg-gray-100 overflow-hidden">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-sm text-gray-400">No image</div>
                                    @endif
                                </div>
                                <div class="flex-1 space-y-1">
                                    <h3 class="font-semibold text-gray-900">{{ $product->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $product->description }}</p>
                                    <div class="text-sm text-gray-500">
                                        Stock: <span class="font-medium text-gray-800">{{ $product->stock_quantity }}</span>
                                    </div>
                                    <div class="text-lg font-semibold text-gray-900">${{ number_format($product->price, 2) }}</div>
                                </div>
                            </div>

                            <div class="mt-3 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <label for="qty-{{ $product->id }}" class="text-sm text-gray-600">Qty</label>
                                    <input
                                        id="qty-{{ $product->id }}"
                                        type="number"
                                        min="1"
                                        class="w-20 rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        wire:model.live="quantities.{{ $product->id }}"
                                    />
                                </div>
                                <button
                                    wire:click="addToCart({{ $product->id }})"
                                    class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                >
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
                <h2 class="text-lg font-semibold text-gray-900">Cart</h2>

                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    @if($cart->items->isEmpty())
                        <p class="text-sm text-gray-500">Your cart is empty.</p>
                    @else
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
                                        <div class="mt-2 flex items-center gap-2">
                                            <input
                                                type="number"
                                                min="0"
                                                class="w-20 rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                value="{{ $item->quantity }}"
                                                wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                                            />
                                            <button
                                                class="text-sm text-red-600 hover:text-red-500"
                                                wire:click="removeItem({{ $item->id }})"
                                            >
                                                Remove
                                            </button>
                                        </div>
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

                        @error('checkout')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <button
                            wire:click="checkout"
                            class="mt-4 w-full inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600"
                        >
                            Checkout
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
