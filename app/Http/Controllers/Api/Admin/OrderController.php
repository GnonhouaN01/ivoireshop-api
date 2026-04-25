<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repository\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderRepository $repository;
    protected OrderService $orderService;

    public function __construct(OrderRepository $repository, OrderService $orderService)
    {
        $this->repository = $repository;
        $this->orderService = $orderService;
    }

    public function index()
    {
        $orders = $this->repository->getAllWithRelations();

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }

    public function show(string $id)
    {
        $order = $this->repository->findOrFail($id);

        return response()->json([
            'success' => true,
            'order' => $order,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $order = $this->repository->findOrFail($id);

        $data = $request->validate([
            'status' => 'required|string',
        ]);

        $updated = $this->orderService->updateStatus($order, $data['status']);

        return response()->json([
            'success' => $updated,
            'order' => $order->fresh(),
        ]);
    }
}
