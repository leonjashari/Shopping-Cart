<?php

declare(strict_types=1);

namespace App\Services\Cart;

use App\Jobs\SendLowStockNotification;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function __construct(private readonly User $user)
    {
    }

    public static function forUser(User $user): self
    {
        return new self($user);
    }

    public function cart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => $this->user->id]);
    }

    public function addProduct(Product $product, int $quantity = 1): Cart
    {
        if ($quantity < 1) {
            throw ValidationException::withMessages(['quantity' => 'Quantity must be at least 1.']);
        }

        $cart = $this->cart();

        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $item->unit_price = $item->unit_price ?? $product->price;
        $newQuantity = ($item->quantity ?? 0) + $quantity;

        if ($product->stock_quantity < $newQuantity) {
            throw ValidationException::withMessages(['stock' => 'Not enough stock for ' . $product->name]);
        }

        $item->quantity = $newQuantity;
        $item->save();

        return $cart->fresh(['items.product']);
    }

    public function updateQuantity(CartItem $item, int $quantity): void
    {
        $this->assertOwnership($item->cart);

        if ($quantity <= 0) {
            $item->delete();
            return;
        }

        if ($item->product->stock_quantity < $quantity) {
            throw ValidationException::withMessages(['stock' => 'Not enough stock for ' . $item->product->name]);
        }

        $item->update(['quantity' => $quantity]);
    }

    public function removeItem(CartItem $item): void
    {
        $this->assertOwnership($item->cart);
        $item->delete();
    }

    public function clear(): void
    {
        $this->cart()->items()->delete();
    }

    public function checkout(): Order
    {
        $cart = $this->cart()->load('items.product');

        if ($cart->items->isEmpty()) {
            throw ValidationException::withMessages(['cart' => 'Cart is empty.']);
        }

        return DB::transaction(function () use ($cart) {
            // Validate stock
            foreach ($cart->items as $item) {
                if ($item->product->stock_quantity < $item->quantity) {
                    throw ValidationException::withMessages([
                        'stock' => "{$item->product->name} does not have enough stock.",
                    ]);
                }
            }

            $order = Order::create([
                'user_id' => $this->user->id,
                'total' => $cart->items->sum(fn (CartItem $item) => $item->quantity * $item->unit_price),
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                ]);

                $product = $item->product;
                $product->decrement('stock_quantity', $item->quantity);
                $product->refresh();

                if ($product->stock_quantity <= $product->low_stock_threshold) {
                    SendLowStockNotification::dispatch($product);
                }
            }

            $cart->items()->delete();

            return $order->load('items.product');
        });
    }

    private function assertOwnership(Cart $cart): void
    {
        if ($cart->user_id !== $this->user->id) {
            abort(403, 'Unauthorized cart access.');
        }
    }
}
