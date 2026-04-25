<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Repository\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderRepository $repository;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $orders = $this->repository->getForUser(Auth::id());

        return response()->json([
            'success' => true,
            'orders' => OrderResource::collection($orders),
        ]);
    }

    public function show(string $orderNumber)
    {
        $order = $this->repository->findForUserByNumber(Auth::id(), $orderNumber);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Commande non trouvée.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'order' => new OrderResource($order),
        ]);
    }
}
