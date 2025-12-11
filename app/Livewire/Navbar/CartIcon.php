<?php

namespace App\Livewire\Navbar;

use App\Services\Cart\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CartIcon extends Component
{
    public array $items = [];
    public int $count = 0;

    public function mount(): void
    {
        $this->refresh();
    }

    #[On('cart-updated')]
    public function refresh(): void
    {
        $cart = CartService::forUser(Auth::user())->cart()->load('items.product');

        $this->count = $cart->items->sum('quantity');
        $this->items = $cart->items
            ->map(fn ($item) => [
                'name' => $item->product->name,
                'quantity' => $item->quantity,
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.navbar.cart-icon');
    }
}
