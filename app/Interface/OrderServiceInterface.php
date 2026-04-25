<?php

namespace App\Interfaces;

use App\Models\Order;
use App\Models\Cart;

interface OrderServiceInterface
{
    public function createFromCart(Cart $cart, array $data): Order;
    public function updateStatus(Order $order, string $status): bool;
    public function calculateTotal(float $subtotal, float $deliveryFee = 0, float $discount = 0): float;
}
