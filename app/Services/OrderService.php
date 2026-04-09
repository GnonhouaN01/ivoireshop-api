<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Create a new order from cart
     */
    public function createFromCart(Cart $cart, array $data): Order
    {
        return DB::transaction(function () use ($cart, $data) {
            $order = Order::create([
                'user_id' => $cart->user_id,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'payment_method' => $data['payment_method'] ?? null,
                'payment_status' => 'unpaid',
                'subtotal' => $data['subtotal'],
                'delivery_fee' => $data['delivery_fee'] ?? 0,
                'discount_amount' => $data['discount_amount'] ?? 0,
                'total' => $data['total'],
                'notes' => $data['notes'] ?? null,
                'shipping_address' => $data['shipping_address'],
            ]);

            // Create order items from cart items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'total_price' => $cartItem->quantity * $cartItem->unit_price,
                    'options' => $cartItem->options,
                    'product_image' => $cartItem->product->images[0] ?? null,
                ]);
            }

            return $order;
        });
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber(): string
    {
        $year = date('Y');
        $lastOrder = Order::orderBy('id', 'desc')->first();
        $nextNumber = ($lastOrder ? intval(substr($lastOrder->order_number, -4)) : 0) + 1;

        return sprintf('IVS-%s-%04d', $year, $nextNumber);
    }

    /**
     * Update order status
     */
    public function updateStatus(Order $order, string $status): bool
    {
        $validStatuses = ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];

        if (!in_array($status, $validStatuses)) {
            return false;
        }

        $updateData = ['status' => $status];

        if ($status === 'paid') {
            $updateData['payment_status'] = 'paid';
            $updateData['paid_at'] = now();
        } elseif ($status === 'shipped') {
            $updateData['shipped_at'] = now();
        } elseif ($status === 'delivered') {
            $updateData['delivered_at'] = now();
        } elseif ($status === 'refunded') {
            $updateData['payment_status'] = 'refunded';
        }

        return $order->update($updateData);
    }

    /**
     * Calculate order total
     */
    public function calculateTotal(float $subtotal, float $deliveryFee = 0, float $discount = 0): float
    {
        return max(0, $subtotal + $deliveryFee - $discount);
    }
}
