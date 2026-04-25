<?php

namespace App\Interfaces;

use App\Models\Order;

interface PaymentServiceInterface
{
    public function initializePayment(Order $order, array $customerData): array;
    public function verifyPayment(string $transactionId): array;
    public function handleNotification(array $data): bool;
    public function processRefund(Order $order, float $amount = null): bool;
    public function validatePaymentData(array $data): bool;
}
