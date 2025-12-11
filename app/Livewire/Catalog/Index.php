<?php

namespace App\Livewire\Catalog;

use App\Models\CartItem;
use App\Models\Product;
use App\Services\Cart\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public array $quantities = [];

    public function mount(): void
    {
        $this->resetQuantities();
    }

    #[On('cart-updated')]
    public function resetQuantities(): void
    {
        $this->quantities = Product::all()
            ->mapWithKeys(fn (Product $product) => [$product->id => 1])
            ->toArray();
    }

    public function addToCart(int $productId): void
    {
        $product = Product::findOrFail($productId);
        $quantity = max(1, (int) ($this->quantities[$productId] ?? 1));

        try {
            CartService::forUser(Auth::user())->addProduct($product, $quantity);
            session()->flash('success', "{$product->name} added to cart.");
            $this->dispatch('cart-updated');
        } catch (ValidationException $e) {
            $this->addError('cart', $e->getMessage());
        }
    }

    public function updateQuantity(int $itemId, int $quantity): void
    {
        $quantity = max(0, $quantity);
        $item = CartItem::findOrFail($itemId);

        try {
            CartService::forUser(Auth::user())->updateQuantity($item, $quantity);
            $this->dispatch('cart-updated');
        } catch (ValidationException $e) {
            $this->addError('cart', $e->validator?->errors()->first() ?? $e->getMessage());
        }
    }

    public function removeItem(int $itemId): void
    {
        $item = CartItem::findOrFail($itemId);
        CartService::forUser(Auth::user())->removeItem($item);
        $this->dispatch('cart-updated');
    }

    public function checkout(): void
    {
        try {
            CartService::forUser(Auth::user())->checkout();
            session()->flash('success', 'Order placed successfully. You will receive a confirmation email shortly.');
            $this->dispatch('cart-updated');
        } catch (ValidationException $e) {
            $this->addError('checkout', $e->validator?->errors()->first() ?? $e->getMessage());
        }
    }

    public function render()
    {
        $products = Product::orderBy('name')->get();
        $cart = CartService::forUser(Auth::user())->cart()->load('items.product');

        return view('livewire.catalog.index', [
            'products' => $products,
            'cart' => $cart,
        ])->layout('layouts.app')->title('Shop');
    }
}
