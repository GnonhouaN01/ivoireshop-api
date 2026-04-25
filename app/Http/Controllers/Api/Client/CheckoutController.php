<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout(
        Request $request,
        CartService $cartService,
        OrderService $orderService,
        PaymentService $paymentService
    ) {
        $data = $request->validate([
            'payment_method' => 'required|string',
            'delivery_fee' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'shipping_address' => 'required|array',
            'shipping_address.address' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.zip_code' => 'required|string',
            'shipping_address.country' => 'required|string',
        ]);

        $response = [
            'success' => false,
            'message' => 'Le panier est vide.',
        ];
        $status = 422;

        $cart = $cartService->getOrCreateCart();

        if ($cart->items()->count() > 0) {
            $subtotal = $cartService->getTotal();
            $deliveryFee = $data['delivery_fee'] ?? 0;
            $discountAmount = $data['discount_amount'] ?? 0;
            $total = $orderService->calculateTotal($subtotal, $deliveryFee, $discountAmount);

            $order = $orderService->createFromCart($cart, [
                'payment_method' => $data['payment_method'],
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'notes' => $data['notes'] ?? null,
                'shipping_address' => $data['shipping_address'],
            ]);

            $paymentData = [
                'name' => Auth::user()->name,
                'surname' => $request->input('customer_surname', ''),
                'email' => Auth::user()->email,
                'phone' => $request->input('customer_phone_number', ''),
                'address' => $data['shipping_address']['address'],
                'city' => $data['shipping_address']['city'],
                'zip_code' => $data['shipping_address']['zip_code'],
                'country' => $data['shipping_address']['country'],
            ];

            if ($paymentService->validatePaymentData($paymentData)) {
                $paymentResult = $paymentService->initializePayment($order, $paymentData);

                if ($paymentResult['success']) {
                    $cartService->clearCart();

                    $response = [
                        'success' => true,
                        'order' => $order,
                        'payment' => $paymentResult,
                    ];
                    $status = 200;
                } else {
                    $response = $paymentResult;
                    $status = 422;
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Données de paiement invalides.',
                ];
                $status = 422;
            }
        }

        return response()->json($response, $status);
    }
}
