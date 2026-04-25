<?php

namespace App\Interfaces;

use App\Models\Cart;
use App\Models\CartItem;

interface CartServiceInterface
{
    public function getOrCreateCart(): Cart;
    public function addItem(int $productId, int $quantity = 1, array $options = []): CartItem;
    public function updateItem(int $itemId, int $quantity): bool;
    public function removeItem(int $itemId): bool;
    public function clearCart(): bool;
    public function getTotal(): float;
}
