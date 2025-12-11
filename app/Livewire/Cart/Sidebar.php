<?php

namespace App\Livewire\Cart;

use App\Services\Cart\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Sidebar extends Component
{
    public $cart;

    public function mount(): void
    {
        $this->refreshCart();
    }

    #[On('cart-updated')]
    public function refreshCart(): void
    {
        $this->cart = CartService::forUser(Auth::user())->cart()->load('items.product');
    }

    public function render()
    {
        return view('livewire.cart.sidebar');
    }
}
