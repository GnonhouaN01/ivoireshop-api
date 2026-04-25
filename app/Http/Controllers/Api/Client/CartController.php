<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(CartService $cartService)
    {
        try {
            $cart = $cartService->getOrCreateCart()->load('items.product');

            return response()->json([
                'success' => true,
                'cart' => $cart,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function add(Request $request, CartService $cartService)
    {
        try {
            $data = $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'nullable|integer|min:1',
            ]);

            $item = $cartService->addItem(
                $data['product_id'],
                $data['quantity'] ?? 1
            );

            return response()->json([
                'success' => true,
                'item' => $item->load('product'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(Request $request, CartService $cartService, string $id)
    {
        try {
            $data = $request->validate([
                'quantity' => 'required|integer|min:0',
            ]);

            $updated = $cartService->updateItem((int) $id, $data['quantity']);

            return response()->json([
                'success' => $updated,
                'message' => $updated ? 'Article mis à jour.' : 'Impossible de mettre à jour.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function remove(string $id, CartService $cartService)
    {
        try {
            $deleted = $cartService->removeItem((int) $id);

            return response()->json([
                'success' => $deleted,
                'message' => $deleted ? 'Article supprimé du panier.' : 'Impossible de supprimer.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function clear(CartService $cartService)
    {
        try {
            $cleared = $cartService->clearCart();

            return response()->json([
                'success' => $cleared,
                'message' => $cleared ? 'Panier vidé.' : 'Impossible de vider le panier.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
