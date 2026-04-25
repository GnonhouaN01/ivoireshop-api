<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function notify(Request $request, PaymentService $paymentService)
    {
        try {
            $handled = $paymentService->handleNotification($request->all());

            return response()->json([
                'success' => $handled,
                'message' => $handled ? 'Notification traitée.' : 'Erreur lors du traitement.',
            ], $handled ? 200 : 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function return(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Retour de paiement CinetPay reçu.',
            'data' => $request->all(),
        ]);
    }
}
