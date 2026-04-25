<?php

namespace App\Services;

use App\Interfaces\PaymentServiceInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class PaymentService implements PaymentServiceInterface
{
    protected $apiUrl = 'https://api-checkout.cinetpay.com/v2/payment';
    protected $verifyUrl = 'https://api-checkout.cinetpay.com/v2/payment/check';

    /**
     * Initialize payment with CinetPay
     */
    public function initializePayment(Order $order, array $customerData): array
    {
        $result = ['success' => false, 'message' => 'Erreur lors de l\'initialisation du paiement'];

        try {
            $payload = [
                'apikey' => Config::get('services.cinetpay.apikey'),
                'site_id' => Config::get('services.cinetpay.site_id'),
                'transaction_id' => $order->order_number,
                'amount' => (int) $order->total,
                'currency' => 'XOF', // Assuming XOF for Ivory Coast
                'description' => 'Paiement commande ' . $order->order_number,
                'notify_url' => route('payment.notify'), // Define this route
                'return_url' => route('payment.return'), // Define this route
                'channels' => 'ALL',
                'customer_id' => $order->user_id,
                'customer_name' => $customerData['name'] ?? '',
                'customer_surname' => $customerData['surname'] ?? '',
                'customer_email' => $customerData['email'] ?? '',
                'customer_phone_number' => $customerData['phone'] ?? '',
                'customer_address' => $customerData['address'] ?? '',
                'customer_city' => $customerData['city'] ?? '',
                'customer_country' => 'CI', // Ivory Coast
                'customer_state' => 'CI',
                'customer_zip_code' => $customerData['zip_code'] ?? '',
                'metadata' => json_encode(['order_id' => $order->id]),
            ];

            $response = Http::post($this->apiUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['code'] === '201') {
                    $order->update([
                        'payment_token' => $data['data']['payment_token'],
                    ]);

                    $result = [
                        'success' => true,
                        'payment_url' => $data['data']['payment_url'],
                        'payment_token' => $data['data']['payment_token'],
                    ];
                } else {
                    Log::error('CinetPay initialization failed: ' . $data['message']);
                    $result['message'] = $data['message'];
                }
            } else {
                Log::error('CinetPay API error: ' . $response->body());
                $result['message'] = 'Erreur API CinetPay';
            }
        } catch (\Exception $e) {
            Log::error('Payment initialization error: ' . $e->getMessage());
        }

        return $result;
    }

    /**
     * Verify payment status
     */
    public function verifyPayment(string $transactionId): array
    {
        $result = ['success' => false, 'message' => 'Erreur lors de la vérification'];

        try {
            $payload = [
                'apikey' => Config::get('services.cinetpay.apikey'),
                'site_id' => Config::get('services.cinetpay.site_id'),
                'transaction_id' => $transactionId,
            ];

            $response = Http::post($this->verifyUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['code'] === '00') {
                    $result = [
                        'success' => true,
                        'status' => $data['data']['status'],
                        'amount' => $data['data']['amount'],
                        'currency' => $data['data']['currency'],
                    ];
                } else {
                    $result['message'] = $data['message'];
                }
            } else {
                $result['message'] = 'Erreur de vérification';
            }
        } catch (\Exception $e) {
            Log::error('Payment verification error: ' . $e->getMessage());
        }

        return $result;
    }

    /**
     * Handle payment notification from CinetPay
     */
    public function handleNotification(array $data): bool
    {
        $handled = false;

        try {
            $transactionId = $data['transaction_id'] ?? null;
            $status = $data['status'] ?? null;

            if ($transactionId && $status) {
                $order = Order::where('order_number', $transactionId)->first();

                if (!$order) {
                    Log::error("Order not found for transaction: {$transactionId}");
                } else {
                    if ($status === 'ACCEPTED') {
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'paid',
                            'paid_at' => now(),
                        ]);

                        Log::info("Payment confirmed for order: {$order->id}");
                        $handled = true;
                    } elseif ($status === 'REFUSED') {
                        $order->update([
                            'payment_status' => 'failed',
                            'status' => 'cancelled',
                        ]);

                        Log::info("Payment refused for order: {$order->id}");
                        $handled = true;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Notification handling error: ' . $e->getMessage());
        }

        return $handled;
    }

    /**
     * Process refund (manual or via CinetPay back-office)
     */
    public function processRefund(Order $order, float $amount = null): bool
    {
        // CinetPay doesn't have direct refund API, refunds are handled via back-office
        // This is a placeholder for manual refund processing

        try {
            $refundAmount = $amount ?? $order->total;

            // Mark as refunded in our system
            $order->update([
                'payment_status' => 'refunded',
                'status' => 'refunded',
            ]);

            Log::info("Refund processed for order {$order->id}, amount: {$refundAmount}");

            return true;
        } catch (\Exception $e) {
            Log::error("Refund failed for order {$order->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate payment data
     */
    public function validatePaymentData(array $data): bool
    {
        return isset($data['customer_name'], $data['customer_email'], $data['customer_phone_number']);
    }
}
