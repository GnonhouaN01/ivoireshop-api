<?php

namespace App\Services;

use App\Interfaces\CartServiceInterface;
use App\Models\Cart;
use App\Models\CartItem;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Illuminate\Support\Facades\Auth;

class CartService implements CartServiceInterface
{
    protected CartRepository $cartRepository;
    protected ProductRepository $productRepository;

    public function __construct(CartRepository $cartRepository, ProductRepository $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Get or create cart for user
     */
    public function getOrCreateCart(): Cart
    {
        $user = Auth::user();
        return $this->cartRepository->getOrCreateForUser($user->id);
    }

    /**
     * Add item to cart
     */
    public function addItem(int $productId, int $quantity = 1, array $options = []): CartItem
    {
        $cart = $this->getOrCreateCart();
        $product = $this->productRepository->findOrFail($productId);

        // Check if item already exists
        $existingItem = $cart->items()->where('product_id', $productId)->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $quantity,
                'options' => array_merge($existingItem->options ?? [], $options),
            ]);
            return $existingItem;
        }

        return CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $productId,
            'quantity' => $quantity,
            'unit_price' => $product->price,
            'options' => $options,
        ]);
    }

    /**
     * Update cart item
     */
    public function updateItem(int $itemId, int $quantity): bool
    {
        $cart = $this->getOrCreateCart();
        $item = $cart->items()->findOrFail($itemId);

        if ($quantity <= 0) {
            return $item->delete();
        }

        return $item->update(['quantity' => $quantity]);
    }

    /**
     * Remove item from cart
     */
    public function removeItem(int $itemId): bool
    {
        $cart = $this->getOrCreateCart();
        $item = $cart->items()->findOrFail($itemId);
        return $item->delete();
    }

    /**
     * Clear cart
     */
    public function clearCart(): bool
    {
        $cart = $this->getOrCreateCart();
        return $this->cartRepository->clear($cart);
    }

    /**
     * Get cart total
     */
    public function getTotal(): float
    {
        $cart = $this->getOrCreateCart();
        return $cart->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }
}
